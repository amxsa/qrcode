<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 9:45
 */
namespace App\Http\Models;

class RoleBusinessModel extends BaseModel{
    protected $table = 'role_business';
    public function __construct()
    {
    }

    public function getByRoleId($role_id){
        if(empty($role_id)){
            throw new \Exception('角色id不能为空',401);
        }
        $query = app('db')
            ->table($this->getTable())
            ->where('role_id',$role_id);
        return $query->get()->all();
    }

    public function getByBusinessUuid($business_uuid){
        if(empty($employee_id)){
            throw new \Exception('员工id不能为空',401);
        }
        $query = app('db')
            ->table($this->getTable())
            ->where('business_uuid',$business_uuid);
        return $query->get()->all();
    }

    public function getByBusinessUuidRoleId($role_id,$business_uuid){
        if(empty($role_id)){
            throw new \Exception('角色id不能为空',401);
        }
        if(empty($business_uuid)){
            throw new \Exception('商户id不能为空',401);
        }
        $query = app('db')
            ->table($this->getTable())
            ->where('role_id',$role_id)
            ->where('business_uuid',$business_uuid);
        return $query->get()->all();

    }
}