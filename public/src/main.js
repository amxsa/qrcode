import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-default/index.css';
import md5 from './js/md5.js';
import Router from 'vue-router';
import App from './App.vue';
import 'font-awesome/css/font-awesome.min.css'

// Vue.use(md5);
Vue.use(ElementUI);
Vue.use(Router);

router.beforeEach((to, from, next) => {
    if (to.path == '/login') {
        sessionStorage.removeItem('user');
    }
    let user = JSON.parse(sessionStorage.getItem('user'));
    if (!user && to.path != '/login') {
        next({
            path: '/login'
        });
    } else {
        next();
    }
});
import {backendPrivilegeList} from './../api/api';
let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
if(localRoutes){
    var leftParams = {
        access_token:localRoutes.access_token
    };
    backendPrivilegeList(leftParams).then(data=>{
        if(data.code==0){
            var list=[];
            for(var i=0;i<data.content.length;i++){
                if(data.content[i].name=="权限管理"){
                    list.push({path:'/',component:require('./page/home.vue'),name: '',iconCls: '',leaf: true,children:[{ path: '/role', component:require('./page/role/role.vue'), name: '权限管理'},{ path: '/roleAuthorization', component:require('./page/role/roleAuthorization.vue'), name: '角色权限配置'}]});
                }else if(data.content[i].name=="商户管理"){
                    list.push({path:'/',component:require('./page/home.vue'),name: '',iconCls: '',leaf: true,children:[{ path: '/Merchant', component:require('./page/Merchant/Merchant.vue'), name: '商户管理'},{ path: '/Merchant_details', component:require('./page/Merchant/Merchant_details.vue'), name: '支付配置'}]});
                }else if(data.content[i].name=="订单管理"){
                    list.push({path: '/',component:require('./page/home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/Order',name: '订单管理',component: (resolve) => require(['./page/Order/Order.vue'], resolve)}]});
                }else if(data.content[i].name=="提现管理"){
                    list.push({path: '/',component:require('./page/home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/Withdrawals',name: '提现管理',component: (resolve) => require(['./page/Withdrawals/Withdrawals.vue'], resolve)}]});
                }else if(data.content[i].name=="支付渠道"){
                    list.push({path: '/',component:require('./page/home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/payment',name: '支付渠道管理',component: (resolve) => require(['./page/payment/payment.vue'], resolve)}]})
                }else if(data.content[i].name=="利益分配"){
                    list.push({path: '/',component:require('./page/home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/Distribution',name: '利益分配',component: (resolve) => require(['./page/Distribution/Distribution.vue'], resolve)},{path:'/DistributionDecord',name: '分配记录',component: (resolve) => require(['./page/Distribution/DistributionDecord.vue'], resolve)},{path:'/RulesDetails',name: '规则列表',component: (resolve) => require(['./page/Distribution/RulesDetails.vue'], resolve)},{path:'/details',name: '规则详情',component: (resolve) => require(['./page/Distribution/details.vue'], resolve)}]});
                }
            }
            router.addRoutes(list);
            window.sessionStorage.removeItem('isLoadNodes')
        }else{
            if(data.code=='402'){
                router.beforeEach((to, from, next) => {
                        next({
                            path: '/login'
                        });
                });
                sessionStorage.removeItem('user');
            }
            // router.go({path:'/login'});
            // router.go({path: '/login',component:require('./page/login.vue'),name:'',hidden: true});
        }
    });
}
// router.beforeEach((route, redirect, next) => {
//     let data = JSON.parse(window.sessionStorage.getItem('user_router'));
//     if(data&&route.path === '/login'){
//         //这里不使用router进行跳转，是因为，跳转到登录页面的时候，是需要重新登录，获取数据的，这个时候，会再次向router实例里面add路由规则，
//         //而next()跳转过去之后，没有刷新页面，之前的规则还是存在的，但是使用location的话，可以刷新页面，当刷新页面的时候，整个app会重新加载
//         //而我们在刷新之前已经把sessionStorage里的user移除了，所以上面的添加路由也不行执行
//         window.sessionStorage.removeItem('user_router');
//         window.sessionStorage.removeItem('isLoadNodes');
//         window.location.href = '/';
//         return false
//     }
//     if (!data && route.path !== '/login') {
//         next({ path: '/login' })
//     } else {
//         if (route.path) {
//             next()
//         } else {
//             next({ path: '/notFound' })
//         }
//     }
// });
//vue选择器
Vue.filter('time', function (value) {
    return new Date(parseInt(value) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
});
// 引入路由
import router from "./router.js"
new Vue({
    el: '#app',
    //template: '<App/>',
    router,
    // store,
    //components: { App }
    render: h => h(App)
}).$mount('#app');