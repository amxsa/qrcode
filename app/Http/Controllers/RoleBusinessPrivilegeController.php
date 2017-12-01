<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 14:20
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleBusinessPrivilegeController extends Controller{
    protected $customerAttributes = [
    ];

    protected $validateRules = [
    ];

    /*
     * 列表接口
     */
    public function actionList(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionList($request));

        return $this->response();
    }

    /*
     * 编辑接口
     */
    public function actionEdit(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionEdit($request));

        return $this->response();
    }

    /*
     * 详情接口
     */
    public function actionView(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionView($request));

        return $this->response();
    }

    /*
     * 新增接口
     */
    public function actionAdd(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionAdd($request));

        return $this->response();
    }

    /*
     * 删除接口
     */
    public function actionDel(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionDel($request));

        return $this->response();
    }

    /*
     * 根据角色查找商户uuid
     */
    public function actionBusinessByRole(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionBusinessByRole($request));

        return $this->response();
    }

    /*
     * 获取权限根据角色和uuid
     */
    public function actionPrivilegeByRoleAndUuid(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionPrivilegeByRoleAndUuid($request));

        return $this->response();
    }

    /*
     * 获取商户列表
     */
    public function actionBusinessList(Request $request){

        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionBusinessList($request));

        return $this->response();
    }
}