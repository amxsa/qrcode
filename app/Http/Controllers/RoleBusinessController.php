<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 15:39
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleBusinessController extends Controller{
    protected $customerAttributes = [
    ];

    protected $validateRules = [
    ];

    public function actionList(Request $request){

        $this->setKeyContent(app('roleBusinessService')->actionList($request));

        return $this->response();
    }
    public function actionEdit(Request $request){

        $this->setKeyContent(app('roleBusinessService')->actionEdit($request));

        return $this->response();
    }
    public function actionView(Request $request){

        $this->setKeyContent(app('roleBusinessService')->actionView($request));

        return $this->response();
    }
    public function actionAdd(Request $request){

        $this->setKeyContent(app('roleBusinessService')->actionAdd($request));
        return $this->response();
    }
}