<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 14:28
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller{
    public function actionList(Request $request){

        $request = [
            'uuid' => 'cf295f28-bc3c-4764-bdb7-1fd972ca3973',
            'ids'  => '5,6,7,8,9,10',
            'access_token' => $request->access_token
        ];
        $this->setKeyContent(app('roleBusinessPrivilegeService')->actionAdd($request));
        return $this->response();
    }

    public function actionView(Request $request){
        $this->setKeyContent(app('businessService')->actionView($request));
        return $this->response();
    }
    public function actionSearch(Request $request){
        $this->setKeyContent(app('businessService')->actionSearch($request));
        return $this->response();
    }
}