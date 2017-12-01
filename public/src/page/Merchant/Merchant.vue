<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <span class="demonstration">商户名称</span>
                <el-input
                        placeholder="请输入商户名称"
                        v-model="MerchantName" style="width:240px;">
                </el-input>
            </li>
            <li>
                <span class="demonstration">联系方式</span>
                <el-input
                        placeholder="请输入手机号码"
                        v-model="Mobile" style="width:240px;">
                </el-input>
            </li>
            <li>
                <span class="demonstration">类目</span>
                <el-select v-model="MerchantList" placeholder="请选择商户类目">
                    <el-option
                            v-for="item in options"
                            :key="item.uuid"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </li>
            <li>
                <span class="demonstration">状态</span>
                <el-select v-model="status" placeholder="请选择订单状态">
                    <el-option v-for="item in DDStatus" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </li>
            <li>
                <el-button type="primary" icon="search" @click="MerchantSearch"></el-button>
            </li>
            <!--<el-button type="info">新增</el-button>-->
        </ul>
        <div class="box_table">
            <el-table v-loading="loadListing" :data="BusinesstableData" border style="width: 100%;text-align: center">
                <el-table-column prop="id" label="#">
                </el-table-column>
                <el-table-column prop="uuid" label="uuid">
                </el-table-column>
                <el-table-column prop="name" label="名称">
                </el-table-column>
                <el-table-column label="logo">
                    <template scope="scope">
                        <img src="../../assets/logo.png" alt="">
                    </template>
                </el-table-column>

                <el-table-column prop="name" label="商户名称">
                </el-table-column>
                <el-table-column prop="general_name" label="类别">
                </el-table-column>
                <el-table-column prop="state" label="状态">
                    <template scope="scope">
                        <el-tag type="primary" v-if="scope.row.state==0">未审核</el-tag>
                        <el-tag type="warning" v-if="scope.row.state==1">启用</el-tag>
                        <el-tag type="danger"  v-if="scope.row.state==2">禁用</el-tag>
                        <el-tag type="danger"  v-if="scope.row.state==3">审核不通过</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="legal_person" label="法人">
                </el-table-column>
                <el-table-column prop="mobile" label="联系方式">
                </el-table-column>
                <el-table-column label="创建时间">
                    <template scope="scope">
                        <!--<span>{{scope.row.update_time | time}}</span>-->
                        <!--二者选一都可以实现-->
                        <span>{{new Date(scope.row.create_time).toLocaleString()}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作">
                    <template scope="scope">
                        <el-button type="text" size="small" @click="details_Click(scope.$index, scope.row)">详情</el-button>
                        <el-button type="text" size="small" v-show="scope.row.state==1" @click="open2(scope.$index, scope.row)">禁用</el-button>
                        <el-button type="text" size="small" v-show="scope.row.state==2" @click="open3(scope.$index, scope.row)">启用</el-button>
                        <el-button type="text" size="small" @click="PaymentSettings(scope.$index, scope.row)">支付配置</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!--商户详情-->
        <el-dialog title="商户详情" v-model="Merchant_Visible" :close-on-click-modal="false">
            <ul class="Single_merchant">
                <li>
                    <h3>商户信息</h3>
                </li>
                <li>
                    <div>商户名称：</div>
                    <div>{{oneBusinesstableData.name}}</div>
                </li>
                <li>
                    <div>入驻平台：</div>
                    <div>{{oneBusinesstableData.general_id}}</div>
                </li>
                <li>
                    <div>商户行政区：</div>
                    <div>广东省深圳市宝安区</div>
                </li>
                <li>
                    <div>商户地址：</div>
                    <div>{{oneBusinesstableData.address}}</div>
                </li>
                <li id="logo_box">
                    <div class="logo_title">
                        <p>商户logo：</p>
                    </div>
                    <div class="logo_picture"><img src="../../assets/logo.png" alt=""></div>
                </li>
                <li id="Merchant_introduction">
                    <div class="MI_title"><p>商户介绍：</p></div>
                    <div class="content">如果你无法简洁的表达你的想法，那只说明你还不够了解它。如果你无法简洁的表达你的想法，那只说明你还不够了解它。如果你无法简洁的表达你的想法，那只说明你还不够了解它。如果你无法简洁的表达你的想法，那只说明你还不够了解它。如果你无法简洁的表达你的想法，那只说明你还不够了解它。如果你无法简洁的表达你的想法，那只说明你还不够了解它。如果你无法简洁的表达你的想法;</div>
                </li>
                <li>
                    <div>联系电话：</div>
                    <div>{{oneBusinesstableData.mobile}}</div>
                </li>
                <li>
                    <div>商户状态：</div>
                    <div v-if="oneBusinesstableData.state==0">未审核</div>
                    <div v-if="oneBusinesstableData.state==1">启用</div>
                    <div v-if="oneBusinesstableData.state==2">禁用</div>
                    <div v-if="oneBusinesstableData.state==3">审核不通过</div>
                </li>
            </ul>
            <ul class="information">
                <li>
                    <h3>资质信息</h3>
                </li>
                <li>
                    <div>法人姓名：</div>
                    <div>{{oneBusinesstableData.legal_person}}</div>
                </li>
                <li id="PictureSize">
                    <div class="sfztp"><p>营业执照：</p></div>
                    <div class="pic">
                        <img src="../../assets/营业执照.jpg" alt="">
                    </div>
                </li>
                <li id="PictureSize">
                    <div class="sfztp"><p>身份证正面照片：</p></div>
                    <div class="pic">
                        <img src="../../assets/sfzbm.png" alt="">
                    </div>
                </li>
                <li id="PictureSize">
                    <div class="sfztp"><p>身份证反面照片：</p></div>
                    <div class="pic"><img src="../../assets/sfzbm.png" alt=""></div>
                </li>
            </ul>
            <ul class="Collection">
                <li>
                    <h3>收款信息</h3>
                </li>
                <li>
                    <div>收款银行名称：</div>
                    <div>中国银行深圳龙华支行</div>
                </li>
                <li>
                    <div>收款银行卡号：</div>
                    <div>1231546215455521231231</div>
                </li>
            </ul>
            <ul v-show="oneBusinesstableData.state==0">
                <li><h3>审核状态</h3></li>
                <li>
                    <el-form ref="form" :model="form" label-width="80px">
                        <el-form-item label="状态：">
                            <el-radio-group v-model="form.resource">
                                <el-radio :label="1">通过</el-radio>
                                <el-radio :label="3">不通过</el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item label="原因：">
                            <el-input type="textarea" v-model="form.desc" style="width:75%"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="AuditPassed">确定</el-button>
                        </el-form-item>
                    </el-form>
                </li>
            </ul>
        </el-dialog>
        <!--支付配置-->
        <el-dialog title="支付方式配置详情" v-model="Payment_settings" :close-on-click-modal="false">
            <ul class="block">
                <li>
                    <span class="demonstration">商户名称</span>
                    <el-input
                            placeholder="请输入商户名称"
                            v-model="MerchantName" style="width:240px;">
                    </el-input>
                </li>
                <li>
                    <span class="demonstration">类型</span>
                    <el-select v-model="type" placeholder="请选择类型">
                        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                        </el-option>
                    </el-select>
                </li>
                <el-button type="info" @click="open_add">新增</el-button>
            </ul>
            <div class="box_table">
                <el-table :data="PaymentAllocation" border style="width: 100%;text-align: center">
                    <el-table-column fixed prop="PaymentName" label="名称" width="120">
                    </el-table-column>
                    <el-table-column label="logo" width="120">
                        <template scope="scope">
                            <img src="../../assets/logo.png" alt="">
                        </template>
                    </el-table-column>
                    <el-table-column prop="PaymentStyle" label="类型" width="120">
                    </el-table-column>
                    <el-table-column prop="PaymentRate" label="折扣率" width="100">
                    </el-table-column>
                    <el-table-column prop="PaymentSHName" label="商户名称" width="120">
                    </el-table-column>
                    <el-table-column prop="PaymentSHSATER" label="状态" width="100">
                        <template scope="scope">
                            <template scope="scope">
                                <el-tag type="primary" v-if="scope.row.state==0">未审核</el-tag>
                                <el-tag type="warning" v-if="scope.row.state==1">启用</el-tag>
                                <el-tag type="danger" v-if="scope.row.state==2">禁用</el-tag>
                            </template>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" width="110">
                        <template scope="scope">
                            <el-button size="small" type="danger" @click="role_Delete(scope.$index, scope.row)">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-dialog>
        <!--支付新增-->
        <el-dialog title="详情" v-model="Payment_Add" :close-on-click-modal="false">
            <ul class="block">
                <li>
                    <span class="demonstration">商户名称</span>
                    <el-input
                            placeholder="请输入商户名称"
                            v-model="MerchantName" style="width:240px;">
                    </el-input>
                </li>
                <li>
                    <span class="demonstration">类型</span>
                    <el-select v-model="type" placeholder="请选择类型">
                        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                        </el-option>
                    </el-select>
                </li>
            </ul>
            <div class="box_table">
                <el-table :data="tableData" border style="width: 100%;text-align: center">
                    <el-table-column fixed prop="province" label="名称" width="120">
                    </el-table-column>
                    <el-table-column label="logo" width="120">
                        <template scope="scope">
                            <img src="../../assets/logo.png" alt="">
                        </template>
                    </el-table-column>
                    <el-table-column prop="zip" label="类型" width="120">
                    </el-table-column>
                    <el-table-column prop="address" label="折扣率" width="300">
                    </el-table-column>
                    <el-table-column label="操作" width="110">
                        <template scope="scope">
                            <el-button size="small" type="info" @click="role_Delete(scope.$index, scope.row)">新增</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-dialog>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {backendBusinessList,AdminBusinessView,adminBusinessStatus,AdminBusinessExamine,backendBusinessGeneralList} from '../../../api/api';
    export default {
        props: {
        },
        data() {
            return {
                type:'',//类型
                Mobile:'',//手机号码
                MerchantName:'',//商户名称
                options: [],
                MerchantList:'',//商户类目
                DDStatus: [{
                    value: '0',
                    label: '未审核'
                }, {
                    value: '1',
                    label: '启用'
                }, {
                    value: '2',
                    label: '禁用'
                }, {
                    value: '3',
                    label: '审核不通过'
                }],
                status:'',//订单状态
                BusinesstableData: [],//商户列表
                oneBusinesstableData:'',
                Merchant_Visible:false,//详情
                PaymentAllocation:[],
                tableData:[],
                form: {
                    resource: '',
                    desc: ''
                },
                addLoading: false,
                Payment_settings:false,//支付配置
                Payment_Add:false,
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                loadListing:true,//加载
            };
        },
        created: function () {
            this.DetailsStart();
            this.Merchant_category();
        },
        methods: {
            handleSizeChange(val) {
                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.DetailsStart();
            },
            handleCurrentChange(val) {
                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.DetailsStart();
            },
            DetailsStart(){
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusinesParams={
                    access_token:localRoutes.access_token,
                    general_id:this.MerchantList,
                    state:this.status,
                    name:this.MerchantName,
                    mobile:this.Mobile,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                backendBusinessList(BusinesParams).then(data=>{
                    if(data.code==0){
                        this.loadListing=false;
                        this.BusinesstableData=data.content.data;
                        this.total=data.content.total;
                        if(data.content.total>10){
                            this.PageDisplay=true;
                        }else{
                            this.PageDisplay=false;
                        }
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                        this.loadListing=false;
                        if(data.code=='402'){
                            _this.$router.push({ path: '/login' });
                            sessionStorage.removeItem('user');
                        }
                    }
                })

            },
            details_Click(index,row) {//详情函数
                this.Merchant_Visible=true;
                this.oneBusinesstableData=row;
                this.form.resource='';
                this.form.desc='';
            },
            PaymentSettings() {//支付配置
//              this.Payment_settings=true;
                var _this=this;
                _this.$router.push({ path: '/Merchant_details' });
            },
            MerchantSearch(){//搜索
                if(this.MerchantList==''&&this.status==''&&this.MerchantName==''&&this.Mobile==''){
                    this.$notify({
                        title: '警告',
                        message: '请输入查询信息',
                        type: 'warning'
                    });
                }else{
                    this.DetailsStart();
                }
            },
            Merchant_category(){
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusinesParams={
                    access_token:localRoutes.access_token
                };
                backendBusinessGeneralList(BusinesParams).then(data=>{
                    console.log(data);
                    if(data.code==0){
                        this.options=data.content;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            },
            open_add(){
//                this.Payment_Add=true;
                const h = this.$createElement;
                this.$msgbox({
                    title: '新增支付管理',
                    message: h('table',{ style: 'border:1px solid #ccc' },
                        [
                        h('tr',{ style: 'border:1px solid #ccc' },[
                          h('th',null,'Month'),
                          h('th',null,'Savings'),
                          h('th',null,'Savings'),
                          h('th',null,'Savings'),
                          h('th',null,'Savings')
                        ]),
                        h('tr',{ style: 'border:1px solid #ccc' },[
                          h('td',null,'January'),
                          h('td',null,'$100'),
                          h('td',null,'$200'),
                          h('td',null,'$300'),
                          h('td',null,'$500')
                        ])
                        ]
                    ),
                    showCancelButton: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            instance.confirmButtonLoading = true;
                            instance.confirmButtonText = '执行中...';
                            setTimeout(() => {
                                done();
                                setTimeout(() => {
                                    instance.confirmButtonLoading = false;
                                }, 300);
                            }, 3000);
                        } else {
                            done();
                        }
                    }
                }).then(action => {
                    this.$message({
                        type: 'info',
                        message: 'action: ' + action
                    });
                });
            },
            open2(index,row){//禁用
                this.$confirm('此操作将禁用该商户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let BusParams={
                        access_token:localRoutes.access_token,
                        state:2,
                        business_uuid:row.uuid
                    };
                    adminBusinessStatus(BusParams).then(data=>{
                        if(data.code==0){
                            this.DetailsStart();
                            this.$message({
                                type: 'success',
                                message: '禁用成功!'
                            });
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消禁用'
                    });
                });
            },
            open3(index,row){//启用
                this.$confirm('此操作将启用该商户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let BusParams={
                        access_token:localRoutes.access_token,
                        state:1,
                        business_uuid:row.uuid
                    };
                    adminBusinessStatus(BusParams).then(data=>{
                        this.DetailsStart();
                        if(data.code==0){
                            this.$message({
                                type: 'success',
                                message: '启用成功!'
                            });
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消启用'
                    });
                });
            },
            modifyState(state,uuid){
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusParams={
                    access_token:localRoutes.access_token,
                    state:state,
                    business_uuid:uuid
                };
                adminBusinessStatus(BusParams).then(data=>{
                    if(data.code==0){
                        this.$message({
                            type: 'success',
                            message: '修改成功!'
                        });
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            },
            AuditPassed(){//审核通过和不通过并说明原因
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                if(this.form.resource==''){
                    this.$notify({
                        title: '警告',
                        message: '请选择状态',
                        type: 'warning'
                    });
                }else if(this.form.resource==3){
                    if(this.form.desc==''){
                        this.$notify({
                            title: '警告',
                            message: '请输入原因',
                            type: 'warning'
                        })
                    }else{
                        let BusParams={
                            access_token:localRoutes.access_token,
                            state:3,
                            business_uuid:this.oneBusinesstableData.uuid
                        };
                        adminBusinessStatus(BusParams).then(data=>{
                            if(data.code==0){
                                this.BusinessExamine(this.form.desc);
                            }else{
                                this.$notify.error({
                                    title: '错误提示',
                                    message: data.message
                                });
                            }
                        });
                    }
                }else{
                    let BusParams={
                        access_token:localRoutes.access_token,
                        state:1,
                        business_uuid:this.oneBusinesstableData.uuid
                    };
                    adminBusinessStatus(BusParams).then(data=>{
                        if(data.code==0){
                            this.DetailsStart();
                            this.oneBusinesstableData.state=1;
                            this.$message({
                                type: 'success',
                                message: '审核成功!'
                            });
                            this.Merchant_Visible=false;
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    });
                }
            },
            BusinessExamine(state){
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusParams={
                    access_token:localRoutes.access_token,
                    examine:state,
                    business_uuid:this.oneBusinesstableData.uuid
                };
                AdminBusinessExamine(BusParams).then(data=>{
                    if(data.code==0){
                        this.oneBusinesstableData.state=3;
                        this.DetailsStart();
                        this.$message({
                            type: 'success',
                            message: '审核成功!'
                        });
                        this.Merchant_Visible=false;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            }
        }
    };
</script>

<style lang="css">
    /*居中*/
    .cell{
        text-align: center;
    }
    /*图片*/
    #PictureSize div{
        height:100px;
        width:120px;
    }
    #PictureSize .pic img{
        width:150px;
        height:100px;
    }
    .sfztp p{
        line-height:100px;
        margin:0;
    }
    #logo_box .logo_picture img{
        height:50px;
    }
    #logo_box div{
        height:50px;
    }
    .logo_title{
        width:80px;
    }
    .logo_title p{
        line-height:50px;
        margin:0;
    }
    li{
        list-style-type:none;
    }
    .block{
        height:auto;
        overflow: hidden;
        margin:0;
    }
    .block li{
        float: left;
    }
    .block input{
        /*width:80%;*/
    }
    .box_table{
        text-align: center;
    }
    /*商户介绍*/
    #Merchant_introduction div{
        height:80px;
    }
    #Merchant_introduction .content{
        width:80%;
        /*overflow-y:scroll;*/
        overflow:auto;
        text-indent:2em;
    }
    .MI_title p{
        line-height:80px;
        margin:0;
    }
    /*商户信息样式*/
    h3{
        margin:10px 0;
    }
    .Single_merchant li{
        height: auto;
        overflow: hidden;
        margin: 10px 0;
    }
    .Single_merchant li div{
        height: 20px;
        float: left;
    }
    .information li{
        height: auto;
        overflow: hidden;
        margin: 10px 0;
    }
    .information li div{
        float: left;
        height: 20px;
    }
    .Collection li{
        height: auto;
        overflow: hidden;
        margin: 10px 0;
    }
    .Collection li div{
        float: left;
        height: 20px;
    }
</style>