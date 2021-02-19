import {hasKey} from "@/libs/tools";
import {Message} from "view-design";
import {getCreditInfo} from "@/api/user";
import dayjs from "dayjs";


//可以用广播来通知分组的信息有所改变
export default {
    state: {
        //用于存放所有分组的消息数据
        smsCredit: 0,
        expireTimeInterval: 600 * 1000,//10分钟过期一次
        expireTime: dayjs().valueOf(),//毫秒
    },
    getters: {},
    mutations: {
        setSmsCredit(state, smsCredit) {
            state.smsCredit = smsCredit;
            state.expireTime = dayjs().valueOf() + state.expireTimeInterval;
        }
    },
    actions: {
        getRemainCredit({commit, state}) {
            return new Promise((resolve, reject) => {
                //判断是否有值
                if (state.expireTime > dayjs().valueOf()) {
                    resolve(state.smsCredit)
                } else {
                    getCreditInfo().then(({data}) => {
                        if (data.code == 0) {
                            commit('setSmsCredit', data.data);
                            resolve(data.data)
                        } else {
                            Message.error(data.message);
                        }
                    }).catch(err => {
                        reject(err)
                    })
                }
            })
        }
    }
}
