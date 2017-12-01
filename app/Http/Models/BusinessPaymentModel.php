<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 9:45
 */
namespace App\Http\Models;

class BusinessPaymentModel extends BaseModel{
    protected $table = 'business_payment';
    public function __construct()
    {
    }

    public function getByBusinessUuid($business_uuid){

        $query = app('db')
            ->table($this->getTable())
            ->where('business_uuid',$business_uuid);
        return $query->get()->all();
    }

    public function getByBusinessUuidPayment($payment_uuid,$business_uuid){
        if(empty($payment_uuid)){
            throw new \Exception('角色id不能为空',401);
        }
        if(empty($business_uuid)){
            throw new \Exception('商户id不能为空',401);
        }
        $query = app('db')
            ->table($this->getTable())
            ->where('payment_uuid',$payment_uuid)
            ->where('business_uuid',$business_uuid)
            ->get();
        return $query;

    }

    public function updateByUuidPayment($business_uuid,$payment_uuid,$state){
        $data['state'] = $state;
        $data['update_at'] = time();
        $query = app('db')
            ->table($this->getTable())
            ->where('payment_uuid',$payment_uuid)
            ->where('business_uuid',$business_uuid)
            ->update($data);
        return $query;
    }
}