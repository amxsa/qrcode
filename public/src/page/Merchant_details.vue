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
                <span class="demonstration">类型</span>
                <el-select v-model="type" placeholder="请选择类型">
                    <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </li>
            <li>
                <span class="demonstration">状态</span>
                <el-select v-model="status" placeholder="请选择订单状态">
                    <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </li>
            <el-button type="info">新增</el-button>
        </ul>
        <div class="box_table">
            <el-table :data="tableData" border style="width: 100%;text-align: center">
                <el-table-column fixed prop="date" label="ID" width="120">
                </el-table-column>
                <el-table-column prop="name" label="uuid" width="120">
                </el-table-column>
                <el-table-column prop="province" label="名称" width="120">
                </el-table-column>
                <el-table-column prop="city" label="logo" width="120">
                </el-table-column>
                <el-table-column prop="address" label="小区" width="300">
                </el-table-column>
                <el-table-column prop="zip" label="类别" width="120">
                </el-table-column>
                <el-table-column prop="address" label="状态" width="300">
                </el-table-column>
                <el-table-column prop="zip" label="创建人" width="120">
                </el-table-column>
                <el-table-column prop="address" label="联系方式" width="300">
                </el-table-column>
                <el-table-column prop="zip" label="创建时间" width="120">
                </el-table-column>
                <el-table-column fixed="right" label="操作" width="110">
                    <template scope="scope">
                        <el-button @click="details_Click" type="text" size="small">详情</el-button>
                        <el-button type="text" size="small">禁用</el-button>
                        <el-button type="text" size="small" @click="">支付配置</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!--新增界面-->
        <el-dialog title="新增权限" v-model="Merchant_Visible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="角色名称" prop="name">
                    <el-input v-model="addForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <!--<el-form-item label="权限说明" prop="author">-->
                <!--<el-input v-model="addForm.author" auto-complete="off"></el-input>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="出版日期">-->
                <!--<el-date-picker type="date" placeholder="选择日期" v-model="addForm.publishAt"></el-date-picker>-->
                <!--</el-form-item>-->
                <el-form-item label="权限说明" prop="description">
                    <el-input type="textarea" v-model="addForm.description" :rows="8"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                type:'',//类型
                Mobile:'',//手机号码
                MerchantName:'',//商户名称
                options: [{
                    value: '选项1',
                    label: '黄金糕'
                }, {
                    value: '选项2',
                    label: '双皮奶'
                }, {
                    value: '选项3',
                    label: '蚵仔煎'
                }, {
                    value: '选项4',
                    label: '龙须面'
                }, {
                    value: '选项5',
                    label: '北京烤鸭'
                }],
                status:'',//订单状态
                tableData: [{
                    date: '2016-05-03',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }, {
                    date: '2016-05-02',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }, {
                    date: '2016-05-04',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }, {
                    date: '2016-05-01',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                },{
                    date: '2016-05-01',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }, {
                    date: '2016-05-01',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }, {
                    date: '2016-05-01',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }, {
                    date: '2016-05-01',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }, {
                    date: '2016-05-01',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1518 弄',
                    zip: 200333
                }],
                Merchant_Visible:false,
            };
        },
        methods: {
            details_Click() {
                this.Merchant_Visible=true;
//                var _this = this;
//                _this.$router.push({ path:'/Merchant_details' });
            }
        }
    };
</script>

<style lang="css">
    li{
        list-style-type:none;
    }
    .block{
        width:100%;
        height:auto;
    }
    .block li{
        float: left;
        margin:0 10px;
    }
    .block input{
        /*width:80%;*/
    }
    .box_table{
        text-align: center;
    }
</style>