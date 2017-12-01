<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31
 * Time: 15:31
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithdrawsController extends Controller{
    public function actionList(Request $request){
        $this->setKeyContent(app('withdrawsService')->actionList($request));
        return $this->response();
    }

    public function actionView(Request $request){
        $this->setKeyContent(app('withdrawsService')->actionView($request));
        return $this->response();
    }

    public function actionSearch(Request $request){
        $this->setKeyContent(app('withdrawsService')->actionSearch($request));
        return $this->response();
    }
    public function actionNotify(Request $request){
        $this->setKeyContent(app('withdrawsService')->actionNotify($request));
        return $this->response();
    }
}