// Import ES6 Promise
require('./bootstrap');
// Import System requirements
window.Vue = require('vue');
import {
  Form,
  HasError,
  AlertError,
  AlertErrors
} from 'vform'
Vue.component(HasError.name, HasError)
Vue.component('OderDetails', require('./components/OrderDetails.vue').default);

import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
Vue.use(datePicker);
import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios';
import Routes from './route.js';

import vSelect from 'vue-select'
Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css';
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate)

// import { bulkList } from 'bootstrap-vue'
// Vue.component('bulkList', require('./components/bulkList.vue').default);

import { LinkPlugin } from 'bootstrap-vue'
Vue.use(LinkPlugin)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
// Import Helpers for filters


// Import Views - Top level
window.Form = Form;


window.axios = axios;

Vue.use(VueRouter)
window.axios = axios;
const app = new Vue({
    data: { isLoading: false },

    el: '#app',

    router: Routes,
});

// Start out app!
// eslint-disable-next-line no-new
new Vue({
  el: '#app',
  router: router,
})

