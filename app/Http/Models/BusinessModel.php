<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/19
 * Time: 10:05
 */
namespace App\Http\Models;

class BusinessModel extends BaseModel{

    protected $table = 'sub_business';
    protected $connection = 'platform';

    public function __construct()
    {
    }

    public function getBusinessAll($page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->select('sub_business.id','sub_business.uuid','sub_business.name','sub_business.logo','general_business.name as general_name'
                ,'sub_business.state','sub_business.mobile','sub_business.create_time','sub_business.legal_person')
            ->join('czy_business_platform.general_business','general_business.id', '=','sub_business.general_id')
            ->paginate($page_size);
        return $data;
    }

    public function getBusinessUuidS($business_uuid,$page_size = 10){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->whereIn('sub_business.uuid',$business_uuid)
            ->join('czy_business_platform.general_business','general_business.id', '=','sub_business.general_id')
            ->select('sub_business.id','sub_business.uuid','sub_business.name','sub_business.logo','general_business.name as general_name'
                ,'sub_business.state','sub_business.mobile','sub_business.create_time','sub_business.legal_person','sub_business.general_id')
            ->paginate($page_size);
        return $data;
    }

    public function getBusinessStatusUuidS($request,$business_uuid,$page_size = 10){

        $name = isset($request['name']) ? $request['name'] : '';

        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
  //          ->where('sub_business.state',1)
            ->where('sub_business.name','like',"%$name%")
            ->whereIn('sub_business.uuid',$business_uuid)
            ->join('general_business','general_business.id', '=','sub_business.general_id')
            ->select('sub_business.id','sub_business.uuid','sub_business.name','sub_business.logo','general_business.name as general_name'
                ,'sub_business.state','sub_business.mobile','sub_business.create_time','sub_business.legal_person','sub_business.general_id');

        if(isset($request['state'])){
            if($request['state'] === '0' || !empty($request['state'])){
                $data->where('sub_business.state',$request['state']);
            }
        }
        if($request['mobile']){
            $data->where('sub_business.mobile',$request['mobile']);
        }
        if($request['general_id']){
            $data->where('sub_business.general_id',$request['general_id']);
        }
        return $data->paginate($page_size);
    }

    public function getBusinessStatusAll($request,$page_size = 10){
        $name = isset($request['name']) ? $request['name'] : '';
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('sub_business.name','like',"%$name%")
            ->join('general_business','general_business.id', '=','sub_business.general_id')
            ->select('sub_business.id','sub_business.uuid','sub_business.name','sub_business.logo','general_business.name as general_name'
                ,'sub_business.state','sub_business.mobile','sub_business.create_time','sub_business.legal_person','sub_business.general_id');
        if($request['mobile']){
            $data->where('sub_business.mobile',$request['mobile']);
        }
        if(isset($request['state'])){
            if($request['state'] === '0' || !empty($request['state'])){
                $data->where('sub_business.state',$request['state']);
            }
        }
        if($request['general_id']){
         $data->where('sub_business.general_id',$request['general_id']);
        }
        return $data->paginate($page_size);
    }

    public function getBusinessByUuid($uuid){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('sub_business.uuid',$uuid)
            ->join('general_business','general_business.id', '=','sub_business.general_id')
            ->select('sub_business.id','sub_business.uuid','sub_business.name','sub_business.logo',
                'general_business.name as general_name','sub_business.state','sub_business.mobile',
                'sub_business.create_time','sub_business.legal_person','sub_business.general_id','sub_business.bank_name',
                'sub_business.bank_card','sub_business.desc','sub_business.address','sub_business.province_code',
                'sub_business.city_code','sub_business.region_code','sub_business.business_license'
                ,'sub_business.identity_num','sub_business.identity_card_back','sub_business.identity_card_font')
            ->first();
        return $data;
    }

    public function search($name){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('name','like',"%$name%")
            ->select('id','name','uuid')
            ->get();
        return $data;
    }

    public function updateStatus($uuid,$data){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->where('uuid',$uuid)
            ->update($data);
        return $data;
    }

    public function getGeneralBusinessList(){
        $data = app('db')
            ->connection($this->connection)
            ->table('general_business')
            ->select('id','name','uuid','customer_id')
            ->get();
        return $data;
    }

    public function getBusinessUuidAndName($business_uuid){
        $data = app('db')
            ->connection($this->connection)
            ->table($this->getTable())
            ->whereIn('sub_business.uuid',$business_uuid)
            ->select('uuid','name')
            ->get();
        return $data;
    }
}