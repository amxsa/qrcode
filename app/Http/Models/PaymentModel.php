<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 14:29
 */
namespace App\Http\Models;

class PaymentModel extends BaseModel{

    protected $table = 'payment';
    protected $connection = 'platform';

    public function __construct()
    {

    }

    public function getPaymentAll($name = '' , $type = '' , $parent_id = '' , $page = 1 , $page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select();
        if($name)
        {
            $data->where('name', 'like', $name);
        }
        if($type)
        {
            $data->where('type' , $type);
        }
        if($parent_id != -1)
        {
            $data->where('parent_id' , $parent_id);
        }
        return $data->paginate($page_size);
    }

    public function getByPaymentUuid($payment_uuid){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('uuid',$payment_uuid)
            ->select()
            ->first();
        return $data;
    }

    public function getNameByPaymentUuid($payment_uuid){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('uuid',$payment_uuid)
            ->select('name')
            ->first();
        return $data;
    }

    public function getByCommunityUuid($community_uuid){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('community_uuid',$community_uuid)
            ->select()
            ->first();
        return $data;
    }
    public function getByPano($pano){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('pano',$pano)
            ->select()
            ->first();
        return $data;
    }
    public function getDefault(){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('default',1)
            ->select()
            ->first();
        return $data;
    }
}