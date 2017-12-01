<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <span class="demonstration">时间</span>
                <el-date-picker v-model="times" type="datetimerange" placeholder="选择时间范围"></el-date-picker>
            </li>
            <li>
                <span class="demonstration">彩之云订单号</span>
                <el-input
                        placeholder="请输入彩之云订单号"
                        v-model="CzyID" style="width:240px;">
                </el-input>
            </li>
            <!--<li>-->
                <!--<span class="demonstration">商户订单号</span>-->
                <!--<el-input-->
                        <!--placeholder="请输入商户订单号"-->
                        <!--v-model="MerchantID" style="width:240px;">-->
                <!--</el-input>-->
            <!--</li>-->
            <li>
                <span class="demonstration">手机号码</span>
                <el-input
                        placeholder="请输入手机号码"
                        v-model="Mobile" style="width:240px;">
                </el-input>
            </li>
            <li>
                <span class="demonstration">商户名称</span>
                <!--<el-select v-model="MerchantName" placeholder="请搜索选择商户名称">-->
                    <!--<el-option v-for="item in SH_NAME" :key="item.value" :label="item.label" :value="item.value">-->
                    <!--</el-option>-->
                <!--</el-select>-->
                <el-select
                        v-model="MerchantName"
                        :multiple="false"
                        filterable
                        remote
                        reserve-keyword
                        placeholder="请输入商户关键词"
                        :remote-method="remoteMethod"
                        :focus="GetFocus"
                        :loading="loading"
                        style="margin-bottom:20px;">
                    <el-option
                            v-for="item in options"
                            :key="item.uuid"
                            :label="item.name"
                            :value="item.name">
                    </el-option>
                </el-select>
            </li>
            <!--<li>-->
                <!--<span class="demonstration">应用名称</span>-->
                <!--<el-select v-model="applicationName" placeholder="请搜索选择应用名称">-->
                    <!--<el-option v-for="item in YY_NAME" :key="item.value" :label="item.label" :value="item.value">-->
                    <!--</el-option>-->
                <!--</el-select>-->
            <!--</li>-->
            <li>
                <span class="demonstration">订单状态</span>
                <el-select v-model="Order_status" placeholder="请选择订单状态">
                    <el-option v-for="item in Order" :key="item.id" :label="item.label" :value="item.id">
                    </el-option>
                </el-select>
            </li>
            <li>
                <el-button type="primary" icon="search" @click="Order_Search"></el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table :data="OrderTableData" v-loading="loadListing" border style="width: 100%">
                <el-table-column prop="shop_name" label="商户名称" width="180">
                </el-table-column>
                <el-table-column prop="colour_sn" label="彩之云订单号" width="300">
                </el-table-column>
                <el-table-column prop="real_total_fee" label="支付金额" width="100">
                </el-table-column>
                <el-table-column prop="payment_name" label="支付方式">
                </el-table-column>
                <el-table-column label="订单状态">
                    <template scope="scope">
                        <el-tag type="primary" v-if="scope.row.trade_state==1">未支付</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==2">已付款</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==3">交易成功</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==4">已关闭</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==5">已撤销</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==6">用户支付中</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==7">转入退款</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==8">支付失败</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="mobile" label="手机号码">
                </el-table-column>
                <el-table-column prop="time_pay" label="创单时间">
                </el-table-column>
                <el-table-column label="操作">
                    <template scope="scope">
                        <el-button @click="details_Click(scope.$index, scope.row)" type="text" size="small">查看</el-button>
                        <el-button type="text" size="small">通知</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <el-dialog title="订单详情" v-model="details_Visible" :close-on-click-modal="false">
            <ul class="OneOrder">
                <li>
                    商户名称：{{OrderTableDataOne.shop_name}}
                </li>
                <!--<li>-->
                    <!--应用名称：{{OrderTableDataOne.general_name}}-->
                <!--</li>-->
                <li>
                    彩之云订单号：{{OrderTableDataOne.colour_sn}}
                </li>
                <li>
                    商户订单号：{{OrderTableDataOne.out_trade_no}}
                </li>
                <li>
                    用户标识：{{OrderTableDataOne.open_id}}
                </li>
                <li>
                    手机号码：{{OrderTableDataOne.mobile}}
                </li>
                <li>
                    订单金额：{{OrderTableDataOne.meal_total_fee}}
                </li>
                <li>
                    支付金额：{{OrderTableDataOne.total_fee}}
                </li>
                <li>
                    支付方式：{{OrderTableDataOne.payment_uuid}}
                </li>
                <li>
                    支付折扣率：{{OrderTableDataOne.discount}}
                </li>
                <li>
                    商品描述：{{OrderTableDataOne.body}}
                </li>
                <li>
                    商品详情：{{OrderTableDataOne.detail}}
                </li>
                <li>终端IP:{{OrderTableDataOne.spbill_create_ip}}</li>
                <li>交易开始时间：{{OrderTableDataOne.time_start}}</li>
                <li>交易结束时间：{{OrderTableDataOne.time_expire}}</li>
                <li>
                    订单状态：{{OrderTableDataOne.trade_state}}
                    <div>

                    </div>
                </li>
                <li>创单时间：{{OrderTableDataOne.time_create}}</li>
                <li>支付时间：{{OrderTableDataOne.time_expire}}</li>
                <!--<li>附加信息：{{OrderTableDataOne.remark}}</li>-->
            </ul>
            <ul class="Store_information">
                <li>小区UUID：{{OrderTableDataOne.community_uuid}}</li>
                <li>应用名称：{{OrderTableDataOne.community_name}}</li>
                <li>门店名称：{{OrderTableDataOne.shop_name}}</li>
                <li>店铺详细地址：{{OrderTableDataOne.address}}</li>
            </ul>
        </el-dialog>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {OrderList,AdminOrderView,businessSearch} from '../../../api/api';
    export default {
        data() {
            return {
                times:'',//时间间隔
                CzyID:'',//彩之云ID
                MerchantID:'',//商户订单号码
                Mobile:'',//手机号码
                MerchantName:'',//商户名称
                options: [],
                list: [],
                loading: false,
                applicationName:'',//应用名称
                Order:[{
                    id:1,
                    label:"未支付"
                },{
                    id:2,
                    label:"已付款"
                },{
                    id:3,
                    label:"交易成功"
                },{
                    id:4,
                    label:"已关闭"
                },{
                    id:5,
                    label:"已撤销"
                },{
                    id:6,
                    label:"用户支付中"
                },{
                    id:7,
                    label:"转入退款"
                },{
                    id:8,
                    label:"支付失败"
                }],
                Order_status:'',//订单状态
                OrderTableData: [],//订单列表
                //详情展示数据
                details_Visible:false,//界面是否显示
                OrderTableDataOne:'',//订单详情
                Detail_data:{//展示数据
                },
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                loadListing:true,//加载
                startTime:'',//开始时间
                endTime:'',//结束时间
            };
        },
        created: function () {
            this.OrderStart();
        },
        watch:{
            options:{
                handler:function(newval,oldval){console.log('...........',newval,oldval)}
            }
        },
        methods: {
            details_Click(index,row) {
                console.log(row);
                this.details_Visible = true;
                this.OrderTableDataOne='';
//                this.OrderTableDataOne=row;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                var OrderListParams ={
                    access_token:localRoutes.access_token,
                    business_uuid:row.business_uuid,
                    colour_sn:row.colour_sn
                };
                AdminOrderView(OrderListParams).then(data => {
                    if(data.code==0){
                        this.OrderTableDataOne=data.content;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                });
            },
            handleSizeChange(val) {
                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.OrderStart();
            },
            handleCurrentChange(val) {
                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.OrderStart();
            },
            remoteMethod(query) {//搜素商户
                if (query !== '') {
                    this.loading = true;
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let MerchantSearch={
                        access_token:localRoutes.access_token,
                        name:query
                    };
                    businessSearch(MerchantSearch).then(data=>{
                        this.loading = false;
                        if(data.code==0){
                            this.options =data.content;
                            if(this.options.length==0){
                                this.MerchantName='';
                            }
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    });
                } else {
                    this.options = [];
                    this.MerchantName='';
                }
            },
            GetFocus(){
                this.options = [];
                this.MerchantName='';
            },
            Order_Search(){//搜索
                ///////////////////////////////////////
                let datdString=this.times[0];
                this.format(this.times[0], 'yyyy-MM-dd HH:mm:ss');
//                datdString = datdString.replace("GMT", "").replaceAll("\\(.*\\)", "");
//                //将字符串转化为date类型，格式2016-10-12
//                SimpleDateFormat format =  new SimpleDateFormat("EEE MMM dd yyyy hh:mm:ss z",Locale.ENGLISH);
//                Date dateTrans = format.parse(datdString);
//                System.out.println(new SimpleDateFormat("yyyy-MM-dd").format(dateTrans))
                ///////////////////////////////////////
                if(this.CzyID==''&&this.Mobile==""&&this.MerchantName==''&&this.Order_status==''&&this.times==''){
                    this.$notify({
                        title: '警告',
                        message: '请输入查询信息',
                        type: 'warning'
                    });
                }else{
                    if(this.times!=''){
                        this.startTime=this.get_unix_time(this.format(this.times[0], 'yyyy-MM-dd HH:mm:ss'));
                        this.endTime=this.get_unix_time(this.format(this.times[1], 'yyyy-MM-dd HH:mm:ss'));
                        this.OrderStart();
                    }else{
                      this.OrderStart();
                    }
//                    this.CzyID='';
//                    this.Mobile="";
//                    this.MerchantName='';
//                    this.Order_status='';
//                    this.times='';
                }
            },
            format(time,format){//转换为日期
                var t = new Date(time);
                var tf = function(i){return (i < 10 ? '0' :'') + i};
                return format.replace(/yyyy|MM|dd|HH|mm|ss/g,function(a){
                    switch(a){
                        case 'yyyy':
                            return tf(t.getFullYear());
                            break;
                        case 'MM':
                            return tf(t.getMonth() + 1);
                            break;
                        case 'mm':
                            return tf(t.getMinutes());
                            break;
                        case 'dd':
                            return tf(t.getDate());
                            break;
                        case 'HH':
                            return tf(t.getHours());
                            break;
                        case 'ss':
                            return tf(t.getSeconds());
                            break;
                    }
                })
            },
            get_unix_time(dateStr){
                let newstr = dateStr.replace(/-/g,'/');
                let date =  new Date(newstr);
                let time_str = date.getTime().toString();
                return time_str.substr(0, 10);
            },
            OrderStart(){
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let OrderListParams ={
                    access_token:localRoutes.access_token,
                    colour_sn:this.CzyID,
                    mobile:this.Mobile,
                    trade_state:this.Order_status,
                    shop_name:this.MerchantName,
                    create_time:this.startTime,
                    end_time:this.endTime,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                OrderList(OrderListParams).then(data => {
                    if(data.code==0){
                        this.loadListing=false;
                        this.OrderTableData=data.content.data;
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
                        if(data.code=='402'){
                            _this.$router.push({ path: '/login' });
                            sessionStorage.removeItem('user');
                        }
                    }
                });

            }
        }
    };
</script>

<style lang="css">
    /*居中*/
    .cell{
        text-align: center;
    }
    li{
        list-style-type:none;
    }
    .block{
        height:auto;
        overflow: hidden;
    }
    .block li{
        float: left;
        margin:10px 10px;
    }
    .block input{
        /*width:80%;*/
    }
    .box_table{
        text-align: center;
    }
    .OneOrder li{
        height:30px;
    }
    .Store_information li{
        height:30px;
    }
</style>