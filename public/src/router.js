import Vue from "vue"
import VueRouter from "vue-router"
// 引入组件
import login from './page/login.vue'
import home from './page/home.vue'
import notFound from './page/404.vue'
import payment from './page/payment/payment.vue'
//支付
import Merchant from './page/Merchant/Merchant.vue'
import Merchant_details from './page/Merchant/Merchant_details.vue'
//商户
import Order from './page/Order/Order.vue'
//订单
import role from './page/role/role.vue'
import roleAuthorization from './page/role/roleAuthorization.vue'
//角色
import Withdrawals from './page/Withdrawals/Withdrawals.vue'
//利益分配
import Distribution from './page/Distribution/Distribution.vue'
import DistributionDecord from './page/Distribution/DistributionDecord.vue'
import RulesDetails from './page/Distribution/RulesDetails.vue'
import details from './page/Distribution/details.vue'
//提现
// 要告诉 vue 使用 vueRouter
Vue.use(VueRouter);
var router = new VueRouter({
    routes:[{
            path: '/login',
            component: login,
            name: '',
            hidden: true
        },{
            path: '/404',
            component: notFound,
            name: '',
            hidden: true
        }
    ]
});
export default router;