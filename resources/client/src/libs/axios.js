import axios from 'axios'
import store from '@/store'
import {Message, Spin} from 'view-design'
import router from '@/router'
import {getToken, setToken} from "@/libs/util";

//设置默认使用csrf
// axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// import { Spin } from 'view-design'
const addErrorLog = errorInfo => {
    const {statusText, status, request: {responseURL}} = errorInfo
    let info = {
        type: 'ajax',
        code: status,
        mes: statusText,
        url: responseURL
    }
    if (!responseURL.includes('save_error_logger')) store.dispatch('addErrorLog', info)
}

class HttpRequest {
    constructor(baseUrl = baseURL) {
        this.baseUrl = baseUrl
        this.queue = {}
    }

    getInsideConfig() {
        const config = {
            baseURL: this.baseUrl,
            headers: {
                //
            }
        }
        return config
    }

    destroy(url) {
        delete this.queue[url]
        if (!Object.keys(this.queue).length) {
            // Spin.hide()
        }
    }

    interceptors(instance, url) {
        // 请求拦截
        instance.interceptors.request.use(config => {
            // 添加全局的loading...
            if (!Object.keys(this.queue).length) {
                // Spin.show() // 不建议开启，因为界面不友好
            }

            //新增请求头部添加token,用于认证
            if (getToken()) {
                config.headers['Authorization'] = 'Bearer ' + getToken();
            }


            this.queue[url] = true
            return config
        }, error => {
            return Promise.reject(error)
        })
        // 响应拦截
        instance.interceptors.response.use(res => {
            this.destroy(url)
            const {data, status, headers} = res
            //获取header//如果有Authorization设置token
            headers.authorization ? setToken(headers.authorization.split(" ")[1]) : "";
            return {data, status, headers}
        }, error => {
            this.destroy(url)
            let errorInfo = error.response
            if (errorInfo === undefined) {
                const {request: {statusText, status}, config} = JSON.parse(JSON.stringify(error))
                errorInfo = {
                    statusText,
                    status,
                    request: {responseURL: config.url}
                }
            } else {
                //laravel登录校验在失败的时候会返回401
                if (errorInfo.status === 401) {
                    Message.error('登录失效，请重新登录!')
                    store.dispatch('handleLogOut').then(() => {
                        router.push({
                            name: 'login'
                        })
                    })
                }
            }
            //发送错误报告,先隐藏
            // addErrorLog(errorInfo)
            return Promise.reject(error)
        })
    }

    request(options) {
        const instance = axios.create()
        options = Object.assign(this.getInsideConfig(), options)
        this.interceptors(instance, options.url)
        return instance(options) //这里已经发出了请求
    }

    fileRequest(options, url, method = 'post') {
        options = Object.assign(this.getInsideConfig(), options)
        const instance = axios.create(options)
        this.interceptors(instance, url)
        if (method == 'post') {
            return instance.post(url, options.CusformData, {
                headers: {'Content-Type': 'multipart/form-data'},
            })
        } else if (method == 'get') {
            return instance.get(url, options.CusformData, {
                headers: {'Content-Type': 'multipart/for    m-data'},
            })
        }

    }

}

export default HttpRequest
