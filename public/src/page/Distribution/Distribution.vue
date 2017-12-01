<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <el-select
                        v-model="Subject_value"
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
                <el-button type="primary" icon="search" @click="BUSHMerchant"></el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table v-loading="loadListing" :data="tableData" border style="width: 100%;">
                <el-table-column prop="id" label="序号">
                </el-table-column>
                <el-table-column prop="general_name" label="商户类目">
                </el-table-column>
                <el-table-column prop="name" label="商户名称">
                </el-table-column>
                <el-table-column label="创建时间">
                    <template scope="scope">
                        <span>{{new Date(scope.row.create_time).toLocaleString()}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="是否可用">
                    <template scope="scope">
                        <el-tag type="primary" v-if="scope.row.state==1">是</el-tag>
                        <el-tag type="danger" v-else="">否</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="操作">
                    <template scope="scope">
                        <el-button size="mini" type="info" @click="RulesDetails_Click(scope.$index, scope.row)">规则详情</el-button>
                        <el-button size="mini" type="success" @click="DistributionDecord_Click(scope.$index, scope.row)">分配记录</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {BusinessList,BackendBusinessStatusList,businessSearch} from '../../../api/api';
    export default {
        props: {
        },
        data() {
            return {
                Subject_value:'',//商户
                options: [],
                list: [],
                loading: false,
                tableData: [],
                loadListing:true,
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
            };
        },
        watch: {
        },
        created: function () {
            this.BusinessStart();
        },
        methods: {
            handleSizeChange(val) {
                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.BusinessStart();
            },
            handleCurrentChange(val) {
                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.BusinessStart();
            },
            BusinessStart(){//商户列表
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusinessParams = {
                    access_token:localRoutes.access_token,
                    name:this.Subject_value,
                    mobile:'',
                    page:this.currentPage,
                    page_size:this.page_size
                };
                BackendBusinessStatusList(BusinessParams).then(data=>{
                    if(data.code==0){
                        this.loadListing=false;
                        this.tableData=data.content.data;
                        this.total=data.content.total;
                        if(data.content.total>10){
                            this.PageDisplay=true;
                        }else{
                            this.PageDisplay=false;
                        }
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message:data.message
                        });
                        if(data.code=='402'){
                            _this.$router.push({ path: '/login' });
                            sessionStorage.removeItem('user');
                        }
                    }
                })
            },
            RulesDetails_Click(index,row) {
                let _this = this;
                _this.$router.push({ path: '/RulesDetails', query:{business_uuid:row.uuid,businessName:row.name} });
            },
            DistributionDecord_Click(index,row){
                let _this = this;
                _this.$router.push({ path: '/DistributionDecord',query:{business_uuid:row.uuid} });
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
                                this.Subject_value='';
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
                    this.Subject_value='';
                }
            },
            GetFocus(){
                this.options = [];
                this.Subject_value='';
            },
            BUSHMerchant(){//添加商户权限
                if(this.options.length==0){
                    this.$notify({
                        title: '警告',
                        message: '请输入搜索信息',
                        type: 'warning'
                    });
                }else{
                    this.BusinessStart();
                    this.options = [];
                    this.Subject_value='';
                }
            },
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
        margin:0 10px;
    }
    .box_table{
        text-align: center;
    }
</style>