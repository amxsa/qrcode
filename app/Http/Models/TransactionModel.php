<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 11:07
 */
namespace App\Http\Models;

class TransactionModel extends BaseModel{

    protected $table = 'transaction';
    protected $connection = 'platform';

    public function __construct($pre)
    {
        $this->table = 'transaction_'.$pre;
    }

    public function getTransactionAll($page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select()
          //  ->leftJoin('sub_business', 'sub_business.uuid', '=', 'withdraws.business_uuid')
            ->leftJoin('payment', 'payment.uuid', '=', $this->getTable().'payment_uuid')
            ->paginate($page_size);
        return $data;
    }

    public function getByTransactionUuid($business_uuid,$page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->whereIn('shop_id',$business_uuid)
            ->select()
            ->paginate($page_size);
        return $data;
    }

    public function getColourTradeNo($colour_sn,$pre){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where($this->getTable().'.colour_sn',$colour_sn)
            ->select()
            ->leftJoin("transaction_child_$pre", "transaction_child_$pre.colour_sn", '=', $this->getTable().'.colour_sn')
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

    public function updateByColourSn($colour_sn,$data){
        return app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('colour_sn' , $colour_sn)
            ->update($data);
    }

    public function updateSuccess($colour_sn,$data){
        return app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('colour_sn' , $colour_sn)
            ->update($data);
    }
}