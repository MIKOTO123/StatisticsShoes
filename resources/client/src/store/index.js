import Vue from 'vue'
import Vuex from 'vuex'

import user from './module/user'
import app from './module/app'
import invoice_sum_enable from './module/invoice_sum_enable'
import remain_credit from './module/remain_credit'


Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        //
    },
    mutations: {
        //
    },
    actions: {
        //
    },
    modules: {
        user,
        invoice_sum_enable,
        remain_credit,
        app,
    }
})
