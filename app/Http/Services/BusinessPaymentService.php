<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 9:30
 */
namespace App\Http\Services;

use App\Http\Models\BusinessPaymentModel;

class BusinessPaymentService extends BaseService{

    protected $cachePrefix = 'role';

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new BusinessPaymentModel();
        }

        return $this->model;
    }

    public function actionList(){
        return $this->getModel()->getAll();
    }

    public function actionAdd($request){

        $data['business_uuid'] = $request['business_uuid'];
        $data['payment_uuid'] = $request['payment_uuid'];
        $data['business_name'] = $request['business_name'];
        $data['payment_name'] = $request['payment_name'];
        $data['create_at'] = time();
        $data['update_at'] = time();
        $data['state'] = 2;
        return $this->getModel()->create($data);
    }

    public function actionState($request){
        if(!$request['business_uuid']){
            throw new \Exception('商户不存在',301);
        }
        if(!$request['payment_uuid']){
            throw new \Exception('支付方式不存在',301);
        }
        if(!$request['state']){
            throw new \Exception('状态值不能为空',301);
        }
        return $this->getModel()->updateByUuidPayment($request['business_uuid'],$request['payment_uuid'],$request['state']);
    }
}