<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 14:20
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
class RoleController extends Controller{
    protected $customerAttributes = [
    ];

    protected $validateRules = [
    ];

    /*
     * 角色列表页
     */
    public function actionList(Request $request){

        $this->setKeyContent(app('roleService')->actionList($request));

        return $this->response();
    }

    /*
     * 角色编辑页
     */
    public function actionEdit(Request $request){

        $this->setKeyContent(app('roleService')->actionEdit($request));

        return $this->response();
    }

    /*
     * 角色详情页
     */
    public function actionView(Request $request){

        $this->setKeyContent(app('roleService')->actionView($request));

        return $this->response();
    }

    /*
     * 增加角色接口
     */
    public function actionAdd(Request $request){

        $this->setKeyContent(app('roleService')->actionAdd($request));

        return $this->response();
    }

    /*
    * 增加角色接口
    */
    public function actionSearch(Request $request){

        $this->setKeyContent(app('roleService')->actionSearch($request));

        return $this->response();
    }
}