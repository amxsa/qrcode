<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 16:26
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivilegeController extends Controller{

    protected $customerAttributes = [
    ];

    protected $validateRules = [
    ];

    /*
     * 权限列表接口
     */
    public function actionList(Request $request){

        $this->setKeyContent(app('privilegeService')->actionList($request));

        return $this->response();
    }

    /*
     * 详情接口
     */
    public function actionView(Request $request){

        $this->setKeyContent(app('privilegeService')->actionView($request));

        return $this->response();
    }

    /*
     * 新增接口
     */
    public function actionAdd(Request $request){

        $this->setKeyContent(app('privilegeService')->actionAdd($request));

        return $this->response();
    }

    /*
     * 编辑接口
     */
    public function actionEdit(Request $request){
        $this->setKeyContent(app('privilegeService')->actionEdit($request));

        return $this->response();
    }

    /*
     * 左侧栏接口
     */
    public function leftTop(Request $request){
        $this->setKeyContent(app('roleBusinessPrivilegeService')->getLeftByRole($request));

        return $this->response();
    }

    /*
     * 获取父级接口
     */
    public function actionGetByParentId(Request $request){
        $this->setKeyContent(app('privilegeService')->actionGetByParentId($request));
        return $this->response();
    }
}