// Import ES6 Promise

// Import System requirements
import Vue from 'vue'
import VueRouter from 'vue-router'

import router from './route.js'

// Import Helpers for filters

// Import Views - Top level

Vue.use(VueRouter)


// Start out app!
// eslint-disable-next-line no-new
new Vue({
  el: '#app',
  router: router,
})
