<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 15:28
 */
namespace App\Http\Models;

class TransactionChildModel extends BaseModel{

    protected $table = 'transaction_child_';
    protected $connection = 'platform';

    public function __construct($pre)
    {
        $this->table = 'transaction_child_'.$pre;
    }

    public function getTransactionAll($business_uuid,$request,$community_uuid,$page_size = 10){
        if($request['create_time']){
            $table = 'transaction_'.date("Ym",$request['create_time']);
        }else{
            $table = 'transaction_'.date("Ym");
        }
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select($table.'.colour_sn',$this->getTable().'.colour_trade_no',$this->getTable().'.time_pay',
                $this->getTable().'.real_total_fee',$this->getTable().'.mobile',$this->getTable().'.trade_state',
                'sub_business.name as shop_name','sub_business.uuid as business_uuid','payment.name as payment_name'
                ,$this->getTable().'.payment_uuid','e1.region_name as e1_name','e2.region_name as e2_name','e3.region_name as e3_name'
                ,$table.'.community_uuid',$this->getTable().'.split_state')
            ->leftJoin('czy_business_platform.payment', 'payment.uuid', '=', $this->getTable().'.payment_uuid')
            ->leftJoin('czy_business_platform.'."$table", $this->getTable().'.colour_sn', '=', $table.'.colour_sn')
            ->leftJoin('czy_business_platform.sub_business', 'sub_business.uuid', '=', $this->getTable().'.business_uuid')
            ->leftJoin('czy_business_platform.entrance_orgs as e1','e1.region_uuid','=',$table.'.community_uuid')
            ->leftJoin('czy_business_platform.entrance_orgs as e2','e2.region_uuid','=','e1.parent_id')
            ->leftJoin('czy_business_platform.entrance_orgs as e3','e3.region_uuid','=','e2.parent_id');
        if($community_uuid){
            $data->whereIn($table.'.community_uuid',$community_uuid);
        }
        if($business_uuid){
            $data->whereIn($this->getTable().'.business_uuid',$business_uuid);
        }
        if($request['colour_sn']){
            $data->where($this->getTable().'.colour_sn',$request['colour_sn']);
        }
        if($request['mobile']){
            $data->where($this->getTable().'.mobile',$request['mobile']);
        }
        if($request['trade_state']){
            $data->where($this->getTable().'.trade_state',$request['trade_state']);
        }
        if($request['shop_name']){
            $data->where('sub_business.name',$request['shop_name']);
        }
        if($request['create_time'] && $request['end_time']){
            $data->whereBetween($this->getTable().'.time_pay',[$request['create_time'],$request['end_time']]);
        }
        return $data->paginate($page_size);
    }

    public function getByTransactionUuid($request,$business_uuid,$page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->whereIn('business_uuid',$business_uuid)
            ->select('colour_sn','colour_trade_no','time_pay','real_total_fee',$this->getTable().'.mobile','trade_state',
                'sub_business.name as shop_name','sub_business.uuid as business_uuid','payment.name as payment_name','payment_uuid')
            ->leftJoin('payment', 'payment.uuid', '=', 'payment_uuid')
            ->leftJoin('sub_business', 'sub_business.uuid', '=', $this->getTable().'.business_uuid');

        if($request['colour_sn']){
            $data->where('colour_sn',$request['colour_sn']);
        }
        if($request['mobile']){
            $data->where($this->getTable().'.mobile',$request['mobile']);
        }
        if($request['trade_state']){
            $data->where('trade_state',$request['trade_state']);
        }
        if($request['shop_name']){
            $data->where('sub_business.name',$request['shop_name']);
        }
        if($request['create_time'] && $request['end_time']){
            $data->whereBetween('time_pay',[$request['create_time'],$request['end_time']]);
        }
        return $data->paginate($page_size);
    }

    public function getColourTradeNo($colour_sn){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('colour_sn',$colour_sn)
            ->select()
            ->first();
        return $data;
    }

    /*
   * 根据colour_sn查询
   */
    public function getByColourSn($colour_sn)
    {
        return app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('colour_sn' , $colour_sn)
            ->select()
            ->first();
    }

    /*
    * 根据colour_sn修改
    */
    public function updateBySn($colour_sn , $data){
        if(!$colour_sn || !$data){ return null; }
        return app('db')->connection($this->connection)->table($this->getTable())->where('colour_sn',$colour_sn)->update($data);
    }


    public function getExcel($business_uuid,$request,$community_uuid){
        if($request['create_time']){
            $table = 'transaction_'.date("Ym",$request['create_time']);
        }else{
            $table = 'transaction_'.date("Ym");
        }
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select($table.'.colour_sn',$this->getTable().'.colour_trade_no',$this->getTable().'.time_pay',
                $this->getTable().'.real_total_fee',$this->getTable().'.mobile',$this->getTable().'.trade_state',
                'sub_business.name as shop_name','sub_business.uuid as business_uuid','payment.name as payment_name'
                ,$this->getTable().'.payment_uuid','e1.region_name as e1_name','e2.region_name as e2_name','e3.region_name as e3_name'
                ,$table.'.community_uuid',$this->getTable().'.split_state')
            ->leftJoin('czy_business_platform.payment', 'payment.uuid', '=', $this->getTable().'.payment_uuid')
            ->leftJoin('czy_business_platform.'."$table", $this->getTable().'.colour_sn', '=', $table.'.colour_sn')
            ->leftJoin('czy_business_platform.sub_business', 'sub_business.uuid', '=', $this->getTable().'.business_uuid')
            ->leftJoin('czy_business_platform.entrance_orgs as e1','e1.region_uuid','=',$table.'.community_uuid')
            ->leftJoin('czy_business_platform.entrance_orgs as e2','e2.region_uuid','=','e1.parent_id')
            ->leftJoin('czy_business_platform.entrance_orgs as e3','e3.region_uuid','=','e2.parent_id');
        if($community_uuid){
            $data->whereIn($table.'.community_uuid',$community_uuid);
        }
        if($business_uuid){
            $data->whereIn($this->getTable().'.business_uuid',$business_uuid);
        }
        if($request['mobile']){
            $data->where($this->getTable().'.mobile',$request['mobile']);
        }
        if($request['trade_state']){
            $data->where($this->getTable().'.trade_state',$request['trade_state']);
        }
        if($request['shop_name']){
            $data->where('sub_business.name',$request['shop_name']);
        }
        if($request['create_time'] && $request['end_time']){
            $data->whereBetween($this->getTable().'.time_pay',[$request['create_time'],$request['end_time']]);
        }
        $return = collect($data->get())->toArray();
        return $return;
    }

}