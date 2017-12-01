<template>
    <div class="login-page-container">
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-position="left" label-width="0px" class="demo-ruleForm login-container">
            <h3 class="title">商户后端登录</h3>
            <el-form-item prop="employee_account">
                <el-input type="text" v-model="ruleForm.employee_account" auto-complete="off" placeholder="账号"></el-input>
            </el-form-item>
            <el-form-item prop="password">
                <el-input type="password" v-model="ruleForm.password" auto-complete="off" placeholder="密码"></el-input>
            </el-form-item>
            <el-checkbox v-model="checked" checked class="remember">记住密码</el-checkbox>
            <el-form-item style="width:100%;">
                <el-button type="primary" style="width:100%;" @click="handleSubmit('ruleForm')" :loading="logining">登录</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<script>
    import {requestLogin,RecordList,SH_Login,backendPrivilegeList} from '../../api/api';
    import md5 from 'js-md5';
    export default {
        props: {
        },
        data() {
            return {
                logining: false,
                ruleForm: {
                    employee_account: '',
                    password: ''
                },
                rules: {
                    employee_account: [{
                        required: true,
                        message: '请输入账号',
                        trigger: 'blur'
                    }],
                    password: [{
                        required: true,
                        message: '请输入密码',
                        trigger: 'blur'
                    }]
                },
                checked: true
            };
        },
        created:function(){
        },
        methods: {
            Token(token){
                var leftParams = {
                    access_token:token
                };
                backendPrivilegeList(leftParams).then(data=>{
                    let _this=this;
                    _this.$router.options.routes='';
                    if(data.code==0){
                        let list=[];
                        for(var i=0;i<data.content.length;i++){
                            console.log(data.content[i]);
                            if(data.content[i].name=="权限管理"){
                                list.push({path:'/',component:require('./home.vue'),name: '',iconCls: '',leaf: true,children:[{ path: '/role', component:require('./role/role.vue'), name: '权限管理'},{ path: '/roleAuthorization', component:require('./role/roleAuthorization.vue'), name: '角色权限配置'}]});
                            }else if(data.content[i].name=="商户管理"){
                                list.push({path:'/',component:require('./home.vue'),name: '',iconCls: '',leaf: true,children:[{ path: '/Merchant', component:require('./Merchant/Merchant.vue'), name: '商户管理'},{ path: '/Merchant_details', component:require('./Merchant/Merchant_details.vue'), name: '支付配置'}]});
                            }else if(data.content[i].name=="订单管理"){
                                list.push({path: '/',component:require('./home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/Order',name: '订单管理',component: (resolve) => require(['./Order/Order.vue'], resolve)}]});
                            }else if(data.content[i].name=="提现管理"){
                                list.push({path: '/',component:require('./home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/Withdrawals',name: '提现管理',component: (resolve) => require(['./Withdrawals/Withdrawals.vue'], resolve)}]});
                            }else if(data.content[i].name=="支付渠道"){
                                list.push({path: '/',component:require('./home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/payment',name: '支付渠道管理',component: (resolve) => require(['./payment/payment.vue'], resolve)}]})
                            }else if(data.content[i].name=="利益分配"){
                                list.push({path: '/',component:require('./home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/Distribution',name: '利益分配',component: (resolve) => require(['./Distribution/Distribution.vue'], resolve)},{path:'/DistributionDecord',name: '分配记录',component: (resolve) => require(['./Distribution/DistributionDecord.vue'], resolve)},{path:'/RulesDetails',name: '规则列表',component: (resolve) => require(['./Distribution/RulesDetails.vue'], resolve)},{path:'/details',name: '规则详情',component: (resolve) => require(['./Distribution/details.vue'], resolve)}]});
                            }
                            if(list.length>=data.content.length){
                                list.length=data.content.length;
//                                console.log(data.content.length);
//                                console.log(list.length);
                                window.sessionStorage.setItem('user_router',JSON.stringify(list));
                                _this.$router.addRoutes(list);
                                _this.$router.push({ path: '/Order' });
//                                console.log(list);
//                                console.log(list.length);
//                                console.log(_this.$router.options.routes.length);
//                                console.log(_this.$router.options.routes);
                                _this.$router.options.routes=list;
                                return;
                            }
                        }
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                });
            },
            login(data){
                window.sessionStorage.setItem('user_router',JSON.stringify(data));
            },
            handleSubmit(ruleForm) {
                sessionStorage.removeItem('user');
                sessionStorage.removeItem('user_router');
                let _this = this;
                _this.$refs[ruleForm].validate((valid) => {
                   if (valid) {
                        _this.logining = true;
                        //验证
                        var loginParams = {
                            employee_account: this.ruleForm.employee_account,
                            password:md5(this.ruleForm.password)
                        };
                        SH_Login(loginParams).then(data=>{
                            if(data.code==0){
                                _this.logining = false;
                                sessionStorage.setItem('user', JSON.stringify(data.content));
                                this.Token(data.content.access_token);
                            }else{
                                _this.logining = false;
                                _this.$alert('用户名或密码错误！', '提示信息', {
                                    confirmButtonText: '确定'
                                });
                            }
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            }
        }
    }
</script>
<style scoped>
    .login-container {
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -moz-border-radius: 5px;
        background-clip: padding-box;
        margin: 180px auto;
        width: 350px;
        padding: 35px 35px 15px;
        background: #fff;
        border: 1px solid #eaeaea;
        box-shadow: 0 0 25px #cac6c6;
    }
    label.el-checkbox.remember {
        margin: 0 0 35px 0;
    }
</style>