<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31
 * Time: 15:30
 */
namespace App\Http\Models;

class WithdrawsModel extends BaseModel{

    protected $table = 'withdraws';
    protected $connection = 'platform';

    public function __construct()
    {
    }

    public function getWithdrawsAll($request,$page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select('sub_business.name','withdraws.id','withdraws.amount','withdraws.fanpiao_amount','withdraws.sq_status',
                'withdraws.business_uuid','withdraws.is_provision','withdraws.notify_url','withdraws.status',
                'withdraws.fp_status','withdraws.update_at','withdraws.crate_at','withdraws.payment_no')
            ->join('sub_business', 'withdraws.business_uuid','=','sub_business.uuid');
        if($request['business_uuid']){
            $data->where('business_uuid',$request['business_uuid']);
        }
        if($request['payment_no']){
            $data->where('payment_no',$request['payment_no']);
        }
        if($request['status']){
            $data->where('status',$request['status']);
        }

        return $data->paginate($page_size);
    }

    public function getByWithdrawsUuid($request,$business_ids,$page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select('sub_business.name','withdraws.id','withdraws.amount','withdraws.fanpiao_amount','withdraws.sq_status',
                'withdraws.business_uuid','withdraws.is_provision','withdraws.notify_url','withdraws.status',
                'withdraws.fp_status','withdraws.update_at','withdraws.crate_at','withdraws.payment_no')
            ->join('sub_business', 'sub_business.uuid', '=', 'withdraws.business_uuid')
            ->whereIn('business_uuid',$business_ids);
        if($request['business_uuid']){
            $data->where('business_uuid',$request['business_uuid']);
        }
        if($request['payment_no']){
            $data->where('payment_no',$request['payment_no']);
        }
        if($request['status']){
            $data->where('status',$request['status']);
        }
        if($request['create_time'] && $request['end_time']){
            $data->whereBetween('crate_at',[$request['create_time'],$request['end_time']]);
        }

        return $data->paginate($page_size);
    }

    public function getByPayNo($pay_no){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('payment_no',$pay_no)
            ->select('sub_business.name','withdraws.id','withdraws.amount','withdraws.fanpiao_amount','withdraws.sq_status',
                'withdraws.business_uuid','withdraws.is_provision','withdraws.notify_url','withdraws.status',
                'withdraws.fp_status','withdraws.update_at','withdraws.crate_at','withdraws.payment_no')
            ->join('sub_business', 'sub_business.uuid', '=', 'withdraws.business_uuid')
            ->first();
        return $data;
    }

    public function search($request){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select('sub_business.name','withdraws.id','withdraws.amount','withdraws.fanpiao_amount','withdraws.sq_status',
                'withdraws.business_uuid','withdraws.is_provision','withdraws.notify_url','withdraws.status',
                'withdraws.fp_status','withdraws.update_at','withdraws.crate_at','withdraws.payment_no')
            ->join('sub_business', 'withdraws.business_uuid','=','sub_business.uuid');
        if($request['business_uuid']){
            $data->where('business_uuid',$request['business_uuid']);
        }
        if($request['payment_no']){
            $data->where('payment_no',$request['payment_no']);
        }
        if($request['status']){
            $data->where('status',$request['status']);
        }
        return $data->paginate(10);
    }

    public function getByPaymentNoAndBusinessUuid($pay_no,$business_uuid){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('payment_no',$pay_no)
            ->where('business_uuid',$business_uuid)
            ->select('id','notify_info')
            ->first();
        return $data;
    }
}