<?php
/**
 * Created by PhpStorm.
 * User: Explorer
 * Date: 2017/7/4
 * Time: 23:18
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PaymentController extends Controller
{
    protected $customerAttributes = [
        'atid' => '金融平台必填参数',
        'pano' => '金融平台必填参数',
        'name' => '名称',
        'logo' => 'logo',
        'type' => '类型',
        'discount' => '折扣率'
    ];

    protected $validateRules = [
        'atid' => 'bail|required|max:50',
        'pano' => 'bail|required|max:50',
        'name' => 'bail|required|max:100',
        'logo' => 'bail|required|max:250',
        'type' => 'bail|required|max:50',
        'discount' => 'bail|required|max:50',
    ];

    /*
     * 搜索列表
     */
    public function searchList(Request $request)
    {
        $this->setContent('list' , app('paymentService')->searchList(
            $request->input('name'),
            $request->input('type'),
            $request->input('parent_id' , -1),
            $request->input('page' , 1),
            $request->input('page_size' , 10)
        ));
        return $this->response();
    }

    /*
     * 搜索列表
     */
    public function actionList(Request $request)
    {
        $this->setContent('list' , app('paymentService')->actionList($request));
        return $this->response();
    }

    /*
     * 新增
     */
    public function actionCreate(Request $request)
    {
        $this->validate(
            $request,
            $this->validateRules,
            $this->validateMessages
        );
        $this->setContent('result' , app('paymentService')->create(
            $request->input('atid'),
            $request->input('pano'),
            $request->input('name'),
            $request->input('logo'),
            $request->input('type'),
            $request->input('discount'),
            $request->input('state'),
            'creater',   //TODO 获取当前用户账号
            $request->input('parent_id')
        ));
        return $this->response();
    }

    /*
     * 修改
     */
    public function actionUpdate(Request $request)
    {
        $data = [];
        if($request->input('name'))
        {
            $data['name'] = $request->input('name');
        }
        if($request->input('logo'))
        {
            $data['logo'] = $request->input('logo');
        }
        if($request->input('state'))
        {
            $data['state'] = $request->input('state');
        }
        $this->setContent('result' , app('paymentService')->update(
            $request->input('id'),
            $data
        ));
        return $this->response();
    }


}