<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 15:39
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessPaymentController extends Controller{
    protected $customerAttributes = [
    ];

    protected $validateRules = [
    ];

    /*
     * 列表接口
     */
    public function actionList(Request $request){

        $this->setKeyContent(app('businessPaymentService')->actionList($request));

        return $this->response();
    }

    /*
     * 编辑接口
     */
    public function actionEdit(Request $request){

        $this->setKeyContent(app('businessPaymentService')->actionEdit($request));

        return $this->response();
    }

    /*
     * 详情接口
     */
    public function actionView(Request $request){

        $this->setKeyContent(app('businessPaymentService')->actionView($request));

        return $this->response();
    }

    /*
     * 增加接口
     */
    public function actionAdd(Request $request){

        $this->setKeyContent(app('businessPaymentService')->actionAdd($request));
        return $this->response();
    }

    /*
     * 状态接口
     */
    public function actionState(Request $request){
        $this->setKeyContent(app('businessPaymentService')->actionState($request));
        return $this->response();
    }
}