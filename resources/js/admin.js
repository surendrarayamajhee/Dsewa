
window.Vue = require('vue');

import VueRouter from 'vue-router';

Vue.use(VueRouter);
const routes = [

    {path:'/hub-list', component:require('./components/hub/hub-list.vue').default},
    {path:'/hub-address/:id', component:require('./components/hub/hub-address.vue').default,name:'hubAddress'},

    {path:'/all-users',component:require('./components/users/all-users').default},
    {path:'/add-comments',component:require('./components/admin/add-comments').default},

]
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})
// router.beforeResolve((to, from, next) => {
//     // If this isn't an initial page load.
//     if (to.name) {
//         // Start the route progress bar.
//         this.$Progress.start();
//     }
//     next()
//   })
  
//   router.afterEach((to, from) => {
//     // Complete the animation of the route progress bar.
//     this.$Progress.finish();
// })
  


export default router;
