<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    protected $customerAttributes = [
        'employee_account' => '账号',
        'password' => '密码',
        'access_token' => 'OAuth 2.0 身份令牌',
    ];

    protected $validateRules = [
        'employee_account' => 'bail|required|max:200',
        'password' => 'bail|required|max:100',
        'access_token' => 'bail|required|size:32',
    ];

    /*
     * 登录接口
     */
    public function login(Request $request)
    {
        $this->validate(
            $request,
            [
                'employee_account' => 'bail|required|max:200',
                'password' => 'bail|required|max:100',
            ],
            $this->validateMessages,
            $this->customerAttributes
        );

        $this->setKeyContent(app('employeeService')->login(
            $request->input('employee_account'),
            $request->input('password')
        ));

        return $this->response();

    }

    public function loginFromPortal(Request $request)
    {

        $this->validate(
            $request,
            [
                'employee_account' => 'bail|required|max:200',
                'access_token' => 'bail|required|size:32',
            ],
            $this->validateMessages,
            $this->customerAttributes
        );

        $this->setContent('employee', app('employeeService')->loginFromPortal(
            $request->input('employee_account'),
            $request->input('access_token')
        ));

        return $this->response();

    }

    /*
     * 登出接口
     */
    public function logout()
    {
        $this->setContent('ok', app('employeeService')->logout() ? '1' : '0');

        return $this->response();

    }

    /*
     * 模糊搜索接口
     */
    public function search(Request $request)
    {
        $this->setContent('employee_list' ,
            app('employeeService')->search(
                $request->input('employee_account'),
                $request->input('name')
            )
        );
        return $this->responseArray();
    }

    /*
     * api模糊搜索接口
     */
    public function apiSearch(Request $request)
    {
        $this->setKeyContent(app('employeeService')->apiSearch(
                $request->input('name')
            )
        );
        return $this->responseArray();
    }

    /*
     * 更新角色接口
     */
    public function updateRoleId(Request $request){
        $this->setKeyContent(app('employeeService')->editRoleId($request));
        return $this->responseArray();
    }

    /*
     * 更新状态接口
     */
    public function updateStatus(Request $request){
        $this->setKeyContent(app('employeeService')->updateStatus($request));
        return $this->responseArray();
    }

    /*
     * 根据access_token获取用户信息接口
     */
    public function actionAccessToken(Request $request){
        $this->setKeyContent(app('employeeService')->actionAccessToken($request));
        return $this->responseArray();
    }

    /*
     * 新增接口
     */
    public function actionAdd(Request $request)
    {
        $this->setKeyContent(app('employeeService')->addEmployeeRole($request));
        return $this->responseArray();
    }

    /*
     * 删除接口
     */
    public function actionDel(Request $request)
    {
        $this->setKeyContent(app('employeeService')->actionDel($request));
        return $this->responseArray();
    }

    /*
     * 根据角色获取员工接口
     */
    public function actionGetByRoleId(Request $request)
    {
        $this->setKeyContent(app('employeeService')->actionGetByRoleId($request));
        return $this->responseArray();
    }

    /*
     * 根据access_token获取员工信息接口
     */
    public function actionGetByAccessToken(Request $request){
        $this->setKeyContent(app('employeeService')->getByAccessToken($request->input('access_token')));
        return $this->responseArray();
    }
}

