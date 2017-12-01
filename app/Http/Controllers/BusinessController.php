<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28
 * Time: 11:02
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessController extends Controller{
    /*
     * 商户列表
     */
    public function actionList(Request $request){
        $this->setKeyContent(app('businessService')->actionList($request));
        return $this->response();
    }

    /*
     * 商户详情
     */
    public function actionView(Request $request){
        $this->setKeyContent(app('businessService')->actionView($request));
        return $this->response();
    }

    /*
     * 商户名称模糊查找
     */
    public function actionSearch(Request $request){
        $this->setKeyContent(app('businessService')->actionSearch($request));
        return $this->response();
    }

    /*
     * 更新商户状态
     */
    public function actionStatus(Request $request){
        $this->setKeyContent(app('businessService')->actionStatus($request));
        return $this->response();
    }

    /*
     * 查找审核通过。未被禁用的商户列表
     */
    public function actionStatusList(Request $request){
        $this->setKeyContent(app('businessService')->actionStatusList($request));
        return $this->response();
    }

    /*
     * 审核不通过接口
     */
    public function actionExamine(Request $request){
        $this->setKeyContent(app('businessService')->actionExamine($request));
        return $this->response();
    }

    /*
     * 获取商户类目接口
     */
    public function actionGeneralBusiness(){
        $this->setKeyContent(app('businessService')->actionGeneralBusiness());
        return $this->response();
    }
}