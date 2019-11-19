window.Vue = require('vue');
import VueRouter from "vue-router";
Vue.use(VueRouter)
const routes = [{
    //new order
        path: "/",
        name: "home",
        component: require('./components/home').default
    },
   
        {
            //new order
                path: "/packets",
                name: "pacekts",
                component: require('./components/Packets').default
            },
            {
                //new order
                    path: "/packet-list",
                    name: "pacektList",
                    component: require('./components/packetList').default
                },
                {
                    //new order
                        path: "/bulkOrder",
                        name: "bulkOrder",
                        component: require('./components/bulkOrder').default
                    },
                    {
                        //new order
                            path: "/bulkList",
                            name: "bulkList",
                            component: require('./components/bulkList').default
                        }, 
                        {
                            //new order
                                path: "/OrderList",
                                name: "OrderList",
                                component: require('./components/OrderList').default
                            },
                            {
                                //new order
                                    path: "/OrderStatus",
                                    name: "OrderStatus",
                                    component: require('./components/OrderStatus').default
                                },
                                {
                                    //new order
                                        path: "/StatuschangeLog",
                                        name: "StatuschangeLog",
                                        component: require('./components/StatuschangeLog').default
                                    }, 
                                    {
                                        //new order
                                            path: "/CustomersList",
                                            name: "CustomersList",
                                            component: require('./components/CustomersList').default
                                        },
                                        {
                                            //new order
                                                path: "/WithDrawl",
                                                name: "WithDrawl",
                                                component: require('./components/WithDrawl').default
                                            }, 
                                            {
                                                //new order
                                                    path: "/Deposit",
                                                    name: "Deposit",
                                                    component: require('./components/Deposit').default
                                                },
                                                {
                                                    //new order
                                                        path: "/profile",
                                                        name: "profile",
                                                        component: require('./components/profile').default
                                                    },
                                                    {
                                                        //new order
                                                            path: "/productType",
                                                            name: "productType",
                                                            component: require('./components/productType').default
                                                        },
                                                        {
                                                            //new order
                                                                path: "/ChangePassword",
                                                                name: "ChangePassword",
                                                                component: require('./components/ChangePassword').default
                                                            },
                                                            {
                                                                //new order
                                                                    path: "/Address",
                                                                    name: "Address",
                                                                    component: require('./components/Address').default
                                                                },
                                                                {
                                                                    //new order
                                                                        path: "/Logout",
                                                                        name: "Logout",
                                                                        component: require('./components/Logout').default
                                                                    },
                                                                    // {
                                                                    //     //new order
                                                                    //         path: "/newcustomer",
                                                                    //         name: "newcustomer",
                                                                    //         component: require('./components/newcustomer').default
                                                                    //     }                

                                                                   //  {
                                                                       //new order
                                                                       //      path: "/popup",
                                                                         //    name: "popup",
                                                                       //      component: require('./components/popup').default
                                                                      //   },                 




]


const router = new VueRouter({
    mode: 'history',
    routes,

})


export default router;