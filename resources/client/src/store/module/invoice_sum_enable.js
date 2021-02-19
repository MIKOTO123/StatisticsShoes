import {hasKey} from "@/libs/tools";
import {Message} from "view-design";
import {getCreditInfo, getInvoiceSumEnable} from "@/api/user";
import dayjs from "dayjs";


//可以用广播来通知分组的信息有所改变
export default {
    state: {
        //用于存放所有分组的消息数据
        invocieSumEnable: 0,
        // expireTimeInterval: 6000 * 1000,//10分钟过期一次
        // expireTime: dayjs().valueOf(),//毫秒
    },
    getters: {},
    mutations: {
        setInvocieSumEnable(state, invocieSumEnable) {
            state.invocieSumEnable = invocieSumEnable;
        }
    },
    actions: {
        getInvoiceSumEnable({commit, state}) {
            return new Promise((resolve, reject) => {
                //判断是否有值
                getInvoiceSumEnable().then(({data}) => {
                    if (data.code == 0) {
                        commit('setInvocieSumEnable', data.data);
                        resolve(data.data)
                    } else {
                        Message.error(data.message);
                    }
                }).catch(err => {
                    reject(err)
                })
            })
        },


    }
}
