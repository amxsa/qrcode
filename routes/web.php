<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return date("Ym");
});
$app->post('employee/login' , ['uses' => 'EmployeeController@login']);
$app->post('business/index' , ['uses' => 'BusinessController@actionIndex']);
$app->get('order/excel' , ['uses' => 'TransactionController@actionExcel']);      //订单报表导出
$app->get('order/org' , ['uses' => 'TransactionController@actionOrg']);
$app->get('order/notify' , ['uses' => 'TransactionController@actionNotify']);
$app->post('employee/login' , ['uses' => 'EmployeeController@login']);
$app->post('test/index' , ['uses' => 'TestController@actionList']);
$app->group(['middleware' => 'externalApiAuth'], function () use ($app)
{

});
$app->group(['prefix' => 'backend', 'middleware'=>['backendAuth'],],function() use ($app)
{
//    $app->get('privilege/left' , ['uses' => 'PrivilegeController@leftTop']);        //列表左侧栏
    $app->post('business/list' , ['uses' => 'BusinessController@actionStatusList']);      //商户列表
    $app->post('business/status/list' , ['uses' => 'BusinessController@actionStatusList']);      //商户审核通过列表
    $app->post('withdraws/list' , ['uses' => 'WithdrawsController@actionList']);    //提现列表
    $app->post('withdraws/search' , ['uses' => 'WithdrawsController@actionSearch']);    //提现列表
    $app->post('order/list' , ['uses' => 'TransactionController@actionList']);      //订单列表
    $app->get('order/org' , ['uses' => 'TransactionController@actionOrg']);      //小区列表
    $app->get('order/excel' , ['uses' => 'TransactionController@actionExcel']);      //订单报表导出
    $app->post('payment/list' , ['uses' => 'PaymentController@searchList']);        //支付方式列表
    $app->post('payment/search' , ['uses' => 'PaymentController@actionSearch']);        //支付方式搜索
    $app->post('business/payment/state' , ['uses' => 'BusinessPaymentController@actionState']);        //商户支付方式禁用启用
    $app->post('business/payment/add' , ['uses' => 'BusinessPaymentController@actionAdd']);        //商户支付方式增加
    $app->post('business/general/list' , ['uses' => 'BusinessController@actionGeneralBusiness']);        //商户支付方式增加
    $app->post('privilege/list' , ['uses' => 'PrivilegeController@actionList']);    //权限列表
    $app->post('business/search' , ['uses' => 'BusinessController@actionSearch']);    //商户搜索
    $app->post('employee/search' , ['uses' => 'EmployeeController@search']);    //员工搜索
    $app->post('employee/api/search' , ['uses' => 'EmployeeController@apiSearch']);    //员工搜索
    $app->post('employee/add/employee' , ['uses' => 'EmployeeController@actionAdd']);    //员工添加
    $app->post('employee/access/token' , ['uses' => 'EmployeeController@actionGetByAccessToken']);    //获取员工信息根据access_token
    $app->post('employee/del' , ['uses' => 'EmployeeController@actionDel']);    //员工搜索
    $app->post('employee/update/role' , ['uses' => 'EmployeeController@updateRoleId']);    //修改员工角色
    $app->post('employee/update/status' , ['uses' => 'EmployeeController@updateStatus']);    //禁用角色
    $app->post('employee/get/role' , ['uses' => 'EmployeeController@actionGetByRoleId']);    //禁用角色
    $app->post('privilege/parent' , ['uses' => 'PrivilegeController@actionGetByParentId']);    //商户搜索
    $app->post('privilege/view' , ['uses' => 'PrivilegeController@actionView']);    //权限详情
    $app->post('privilege/add' , ['uses' => 'PrivilegeController@actionAdd']);      //增加权限
    $app->post('privilege/edit' , ['uses' => 'PrivilegeController@actionEdit']);    //修改权限
    $app->post('role/list' , ['uses' => 'RoleController@actionList']);               //角色列表
    $app->post('role/add' , ['uses' => 'RoleController@actionAdd']);                //增加角色
    $app->post('role/edit' , ['uses' => 'RoleController@actionEdit']);              //编辑角色
    $app->post('role/view' , ['uses' => 'RoleController@actionView']);              //角色详情
    $app->post('role/search' , ['uses' => 'RoleController@actionSearch']);              //角色搜索
    $app->post('role/privilege/list' , ['uses' => 'RoleBusinessPrivilegeController@actionList']);//角色商户权限绑定列表
    $app->post('role/privilege/add' , ['uses' => 'RoleBusinessPrivilegeController@actionAdd']);//角色商户权限绑定增加
    $app->post('role/privilege/edit' , ['uses' => 'RoleBusinessPrivilegeController@actionEdit']);//角色商户权限绑定编辑
    $app->post('role/privilege/del' , ['uses' => 'RoleBusinessPrivilegeController@actionDel']);//角色商户权限绑定删除
    $app->post('role/privilege/business/api' , ['uses' => 'RoleBusinessPrivilegeController@actionBusinessList']);//根据access_token获取当前商户列表
    $app->post('role/privilege/business' , ['uses' => 'RoleBusinessPrivilegeController@actionBusinessByRole']);//角色商户权限绑定删除
    $app->post('role/privilege/business/role' , ['uses' => 'RoleBusinessPrivilegeController@actionPrivilegeByRoleAndUuid']);//角色商户权限绑定删除
});


$app->group(['prefix' => 'admin', 'middleware'=>['backendRole'],],function() use ($app)
{
    $app->post('role/business/test' , ['uses' => 'RoleBusinessController@test']);
    $app->post('business/view' , ['uses' => 'BusinessController@actionView']);
    $app->post('business/edit' , ['uses' => 'BusinessController@actionEdit']);
    $app->post('business/examine' , ['uses' => 'BusinessController@actionExamine']);
    $app->post('business/status' , ['uses' => 'BusinessController@actionStatus']);
    $app->post('withdraws/view' , ['uses' => 'WithdrawsController@actionView']);
    $app->post('withdraws/notify' , ['uses' => 'WithdrawsController@actionNotify']);
    $app->post('order/view' , ['uses' => 'TransactionController@actionView']);
    $app->post('order/notify' , ['uses' => 'TransactionController@actionNotify']);
});