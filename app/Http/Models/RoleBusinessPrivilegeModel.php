<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 14:20
 */
namespace App\Http\Models;

class RoleBusinessPrivilegeModel extends BaseModel{
    protected $table = 'role_business_privilege';
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

    public function getByPrivilegeRole($role_id,$privilege_id){
        if(empty($role_id)){
            throw new \Exception('角色id不能为空',401);
        }
        if(empty($privilege_id)){
            throw new \Exception('权限不能为空',401);
        }
        $query = app('db')
            ->table($this->getTable())
            ->where('role_id',$role_id)
            ->where('privilege_id',$privilege_id);
        return $query->get()->all();

    }

    public function getByBusinessUuidRoleIdPrivilegeId($role_id,$business_uuid,$privilege_id){
        if(empty($role_id)){
            throw new \Exception('角色id不能为空',401);
        }
        if(empty($business_uuid)){
            throw new \Exception('商户uuid不能为空',401);
        }
        if(empty($privilege_id)){
            throw new \Exception('权限id不能为空',401);
        }
        $query = app('db')
            ->table($this->getTable())
            ->where('role_id',$role_id)
            ->where('business_uuid',$business_uuid)
            ->where('privilege_id',$privilege_id)
            ->select()
            ->first();
        return $query;
    }

    public function getTopByRole($role_id){
        $query = app('db')
            ->table($this->getTable())
            ->where('role_id',$role_id)
            ->join('privilege','role_business_privilege.privilege_id', '=','privilege.id')
            ->where('privilege.parent_id',0)
            ->groupBy('privilege_id')
            ->get()->all();
        return $query;
    }

    public function deleteByUuid($uuid,$role_id){
        $query = app('db')
            ->table($this->getTable())
            ->where('business_uuid',$uuid)
            ->where('role_id',$role_id)
            ->delete();
        return $query;
    }

    public function getBusinessByRoleId($role_id){
        if(empty($role_id)){
            throw new \Exception('角色id不能为空',401);
        }

        $query = app('db')
            ->table($this->getTable())
            ->where('role_id',$role_id)
//            ->join('czy_business_platform.sub_business','role_business_privilege.business_uuid', '=','sub_business.uuid')
           // ->join('privilege','role_business_privilege.privilege_id', '=','privilege.id')
            ->groupBy('business_uuid')
            ->select('business_uuid')
            ->get();
        return $query;
    }

    public function getPrivilegeByRoleAndUuid($role_id,$business_uuid){
        $query = app('db')
            ->table($this->getTable())
            ->where('role_id',$role_id)
            ->where('business_uuid',$business_uuid)
             ->join('privilege','role_business_privilege.privilege_id', '=','privilege.id')
            ->select('business_uuid','privilege.name as privilege_name','privilege.id as privilege_id','privilege.parent_id')
            ->get();
        return $query;
    }
}