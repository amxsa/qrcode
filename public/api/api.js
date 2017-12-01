/**
 * Created by jerry on 2017/09/29.
 */
import axios from 'axios'
import qs from 'qs';

let base = 'http://iceapi.colourlife.com:8081/v1/czywg/employee';
// let base ="http://single-czytest.colourlife.com/application";
let url='http://colourhome-czytest.colourlife.com'



export const requestLogin = params => { return axios.post(`${base}/login`, qs.stringify(params)).then(res => res.data) };
// export const requestLogin = params => { return axios.get(`${base}/KDCX/Expess`, params).then(res => res.data) };


// export const getRecordList = params => { return axios.get(`http://colourhome-czytest.colourlife.com/backend/getMyOptions`,{ params: params }); };
// export const RecordList = params => { return axios.get(`http://colourhome-czytest.colourlife.com/backend/getMyOptions`, qs.stringify(params)).then(res => res.data) };
export const RecordList = params => { return axios.get(`http://colourhome-czytest.colourlife.com/backend/getMyOptions`,params).then(res => res.data); };
//商户接口
let sh_url='http://check.account.czytest.colourlife.com';
//用户登录
export const SH_Login = params => { return axios.post(`${sh_url}/employee/login`, qs.stringify(params)).then(res => res.data) };
//支付列表
export const backendPaymentList = params => { return axios.post(`${sh_url}/backend/payment/list`, qs.stringify(params)).then(res => res.data) };
//获取商户类目
export const backendBusinessGeneralList = params => { return axios.post(`${sh_url}/backend/business/general/list`, qs.stringify(params)).then(res => res.data) };
//支付——新增
export const paymentAdd = params => { return axios.post(`${sh_url}/payment/add`, qs.stringify(params)).then(res => res.data) };
//支付——修改支付方式
export const paymentEdit = params => { return axios.post(`${sh_url}/payment/edit`, qs.stringify(params)).then(res => res.data) };
//提现列表
export const backendWithdrawsList = params => { return axios.post(`${sh_url}/backend/withdraws/list`, qs.stringify(params)).then(res => res.data) };
//提现——详情
export const adminWthdrawsView = params => { return axios.post(`${sh_url}/admin/withdraws/view`, qs.stringify(params)).then(res => res.data) };
//提现通知
export const adminNotify = params => { return axios.post(`${sh_url}/admin/withdraws/notify`, qs.stringify(params)).then(res => res.data) };
//权限列表
export const backendPrivilegeList = params => { return axios.post(`${sh_url}/backend/privilege/list`, qs.stringify(params)).then(res => res.data) };
//角色管理列表
export const backendRoleList = params => { return axios.post(`${sh_url}/backend/role/list`, qs.stringify(params)).then(res => res.data) };
//角色新增
export const backendRoleAdd = params => { return axios.post(`${sh_url}/backend/role/add`, qs.stringify(params)).then(res => res.data) };
//模糊搜索员工
export const EmployeeApiSearch = params => { return axios.post(`${sh_url}/backend/employee/api/search`, qs.stringify(params)).then(res => res.data) };
//员工OA账号绑定与角色（删除oa账号修改）
export const employeeUpdateRole = params => { return axios.post(`${sh_url}/backend/employee/update/role`, qs.stringify(params)).then(res => res.data) };
//绑定oa账号
export const employeeAddEmployee = params => { return axios.post(`${sh_url}/backend/employee/add/employee`, qs.stringify(params)).then(res => res.data) };
//查看已绑定角色OA账号
export const employeeGetRole = params => { return axios.post(`${sh_url}/backend/employee/get/role`, qs.stringify(params)).then(res => res.data) };
//删除员工角色绑定
export const employeeUpdateStatus = params => { return axios.post(`${sh_url}/backend/employee/update/status`, qs.stringify(params)).then(res => res.data) };
export const backendEmployeeDel = params => { return axios.post(`${sh_url}/backend/employee/del`, qs.stringify(params)).then(res => res.data) };
//查看已绑定商户接口
export const rolePrivilegeBusiness = params => { return axios.post(`${sh_url}/backend/role/privilege/business`, qs.stringify(params)).then(res => res.data) };
//查看已绑定权限列表
export const RolePrivilegeBusinessRole = params => { return axios.post(`${sh_url}/backend/role/privilege/business/role`, qs.stringify(params)).then(res => res.data) };
//角色搜索
export const backendRoleSearch = params => { return axios.post(`${sh_url}/backend/role/search`, qs.stringify(params)).then(res => res.data) };
//审核不通过
export const AdminBusinessExamine = params => { return axios.post(`${sh_url}/admin/business/examine`, qs.stringify(params)).then(res => res.data) };
//角色和商户ID绑定
export const RolePrivilegeAdd = params => { return axios.post(`${sh_url}/backend/role/privilege/add`, qs.stringify(params)).then(res => res.data) };
//删除角色商户绑定关系
export const rolePrivilegeDel = params => { return axios.post(`${sh_url}/backend/role/privilege/del`, qs.stringify(params)).then(res => res.data) };
//父级获取子级权限
export const backendPrivilegeParent = params => { return axios.post(`${sh_url}/backend/privilege/parent`, qs.stringify(params)).then(res => res.data) };
//商户模糊搜索
export const businessSearch = params => { return axios.post(`${sh_url}/backend/business/search`, qs.stringify(params)).then(res => res.data) };
//商户管理列表
export const backendBusinessList = params => { return axios.post(`${sh_url}/backend/business/list`, qs.stringify(params)).then(res => res.data) };
//商户状态更改接口admin/business/status
export const adminBusinessStatus = params => { return axios.post(`${sh_url}/admin/business/status`, qs.stringify(params)).then(res => res.data) };
//商户详情
export const AdminBusinessView = params => { return axios.post(`${sh_url}/admin/business/view`, qs.stringify(params)).then(res => res.data) };
//查看商户 启用，审核通过 列表供分账系统用
export const BackendBusinessStatusList = params => { return axios.post(`${sh_url}/backend/business/status/list`, qs.stringify(params)).then(res => res.data) };
//订单管理列表
export const OrderList = params => { return axios.post(`${sh_url}/backend/order/list`,qs.stringify(params)).then(res => res.data)};
//订单详情
export const AdminOrderView = params => { return axios.post(`${sh_url}/admin/order/view`,qs.stringify(params)).then(res => res.data)};
//分账url
let fz_url='http://fzsvr-czytest.colourlife.com';
//分账规则--列表--查看
export const RuleList = params => { return axios.post(`${fz_url}/backend/rule/list`, qs.stringify(params)).then(res => res.data) };
//科目列表
export const TagList = params => { return axios.post(`${fz_url}/backend/tag/list`, qs.stringify(params)).then(res => res.data) };
//分账规则新增
export const RuleAdd = params => { return axios.post(`${fz_url}/backend/rule/add`, qs.stringify(params)).then(res => res.data) };
//分账规则修改禁用
export const RuleEdit = params => { return axios.post(`${fz_url}/backend/rule/edit`, qs.stringify(params)).then(res => res.data) };
//岗位--列表
export const JobList = params => { return axios.post(`${fz_url}/backend/job/list`, qs.stringify(params)).then(res => res.data) };
//组织架构--列表--模糊搜索
export const OrgList = params => { return axios.post(`${fz_url}/backend/org/list`, qs.stringify(params)).then(res => res.data) };
//分成方--新增
export const SplitAdd = params => { return axios.post(`${fz_url}/backend/split/add`, qs.stringify(params)).then(res => res.data) };
//分成方--修改、禁用
export const SplitEdit = params => { return axios.post(`${fz_url}/backend/split/edit`, qs.stringify(params)).then(res => res.data) };
//分成方--列表--查看
export const SplitList = params => { return axios.post(`${fz_url}/backend/split/list`, qs.stringify(params)).then(res => res.data) };
//分成记录--列表--查看（根据商户类目、具体商户查询）
export const SplitLogList = params => { return axios.post(`${fz_url}/backend/split/log/list`, qs.stringify(params)).then(res => res.data) };
export const TagOrderList = params => { return axios.post(`${fz_url}/backend/tag/order/list`, qs.stringify(params)).then(res => res.data) };
//商户列表
export const BusinessList = params => { return axios.post(`${fz_url}/backend/business/list`, qs.stringify(params)).then(res => res.data) };
//商户已创建规则的科目列表（用于分账规则--新增--选择已有规则）
export const BusinessTagList = params => { return axios.post(`${fz_url}/backend/business/tag/list`, qs.stringify(params)).then(res => res.data) };
