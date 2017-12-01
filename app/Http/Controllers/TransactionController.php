<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 11:42
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller{

    public function actionList(Request $request){
        $this->setKeyContent(app('transactionService')->actionList($request));
        return $this->response();
    }

    public function actionView(Request $request){
        $this->setKeyContent(app('transactionService')->actionView($request));
        return $this->response();
    }
    public function actionNotify(Request $request){
        $this->setKeyContent(app('transactionService')->actionNotify($request));
        return $this->response();
    }

    public function actionExcel(Request $request){
        $this->setKeyContent(app('transactionService')->actionExcel($request));
        return $this->response();
    }

    public function actionOrg(Request $request){
        $this->setKeyContent(app('transactionService')->actionOrg($request));
        return $this->response();
    }
}