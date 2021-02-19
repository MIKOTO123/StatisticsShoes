import Vue from 'vue'
import Router from 'vue-router'
import routes from './routers'
import store from '@/store'
import bus from '@common@/bus'
// import iView from 'iview'
import ViewUI from 'view-design';
import {setToken, getToken, canTurnTo, setTitle} from '@/libs/util'
import config from '@/config'
import {GROUPLIST_LOADING_COMPLETED} from "@common@/bus/bus-constant";

const {homeName} = config

//防止跳转自身路由报错
const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}
//防止跳转自身路由报错


Vue.use(Router)
const router = new Router({
    routes,
    mode: 'history'
})
const LOGIN_PAGE_NAME = 'login'
const REGISTER_PAGE_NAME = 'register'
const FORGET_PWD_PAGE_NAME = 'forgetpwd'
const REGISTER_PROTOCOL_PAGE_NAME = 'registe_protocol'

const turnTo = (to, access, next) => {
    // init()
    if (canTurnTo(to.name, access, routes)) next() // 有权限，可访问
    else next({replace: true, name: 'error_401'}) // 无权限，重定向到401页面
}

/**
 *  异步加载初始化数据
 */
const init = () => {
    //加载分组信息
    store.dispatch('getGroupList').then(data => {
        bus.$emit(GROUPLIST_LOADING_COMPLETED, data)
    })
    //加载发票信息
    store.dispatch('getInvoiceInfo').then(data => {
    })
    //加载地址信息
    store.dispatch('getAddressesInfo').then(data => {
    })
}

router.beforeEach((to, from, next) => {
    ViewUI.LoadingBar.start()
    const token = getToken()


    //先做几个个例给小p用
    if ([REGISTER_PAGE_NAME, FORGET_PWD_PAGE_NAME, REGISTER_PROTOCOL_PAGE_NAME].indexOf(to.name) != -1) {
        return next();
    }
    if (!token && to.name !== LOGIN_PAGE_NAME) {
        // 未登录且要跳转的页面不是登录页
        next({
            name: LOGIN_PAGE_NAME // 跳转到登录页
        })
    } else if (!token && to.name === LOGIN_PAGE_NAME) {
        // 未登陆且要跳转的页面是登录页
        next() // 跳转
    } else if (token && to.name === LOGIN_PAGE_NAME) {
        // 已登录且要跳转的页面是登录页
        next({
            name: homeName // 跳转到homeName页
        })
    } else {
        if (store.state.user.hasGetInfo) {
            turnTo(to, store.state.user.access, next)
        } else {
            store.dispatch('getUserInfo').then(user => {
                // 拉取用户信息，通过用户权限和跳转的页面的name来判断是否有权限访问;access必须是一个数组，如：['super_admin'] ['super_admin', 'admin']
                turnTo(to, user.access, next)
            }).catch(() => {
                setToken('')
                next({
                    name: 'login'
                })
            })
        }
    }
})

router.afterEach(to => {
    setTitle(to, router.app)
    ViewUI.LoadingBar.finish()
    window.scrollTo(0, 0)
})

export default router
