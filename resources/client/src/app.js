// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'

import 'es6-promise/auto';
//引入babel-polyfill 支持一些ie浏览器transform
import 'babel-polyfill/';

import App from './app.vue'
import router from './router'
import store from './store'
import bus from '@common@/bus'
// import iView from 'iview'
import ViewUI from 'view-design';
import i18n from '@/locale'
import config from '@/config'
import importDirective from '@/directive'
import {directive as clickOutside} from 'v-click-outside-x'
import installPlugin from '@/plugin'
import './index.less'
import '@/assets/icons/iconfont.css'
import TreeTable from 'tree-table-vue'
import VOrgTree from 'v-org-tree'
import 'v-org-tree/dist/v-org-tree.css'
import 'view-design/dist/styles/iview.css';//升级引入viewdesign的包


import * as filters from '@/libs/filters.js'






// 实际打包时应该不引入mock
/* eslint-disable */
//先不引入mock模块
// if (process.env.NODE_ENV !== 'production') require('@/mock')

Vue.use(ViewUI, {
    i18n: (key, value) => i18n.t(key, value)
})
Vue.use(TreeTable)
Vue.use(VOrgTree)
/**
 * @description 注册admin内置插件
 */
installPlugin(Vue)
/**
 * @description 生产环境关掉提示
 */
Vue.config.productionTip = false
/**
 * @description 全局注册应用配置
 */
Vue.prototype.$config = config
/**
 * 设置一个bus事件总线
 * @type {Vue | CombinedVueInstance<Vue, object, object, object, Record<never, any>>}
 */
Vue.prototype.$bus = bus;

/**
 * 引入全局过滤函数
 */
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])//插入过滤器名和对应方法
})

/**
 * 注册指令
 */
importDirective(Vue)
Vue.directive('clickOutside', clickOutside)

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    i18n,
    store,
    render: h => h(App)
})
