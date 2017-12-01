<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 9:30
 */
namespace App\Http\Services;

use App\Http\Models\RoleBusinessModel;

class RoleBusinessService extends BaseService{

    protected $cachePrefix = 'role';

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new RoleBusinessModel();
        }

        return $this->model;
    }

    public function actionList(){
        return $this->getModel()->getAll();
    }

    public function actionAdd($request){

//        if(empty($request['role_id'])){
//            throw new \Exception('角色ID不能为空',201);
//        }
//        $role = app('roleService')->getModel()->getByid($request['role_id']);
//        if(empty($role)){
//            throw new \Exception('绑定的角色不存',201);
//        }

        $ids_array=explode(',',$request['ids']);
        foreach ($ids_array as $k=>$value){
            $data = [];
            $data['business_uuid'] = $request['uuid'];
            $data['role_id'] = $request['uuid'];
            $data['privilege_id'] = $request['uuid'];
            $data['create_at'] = time();
            $data['update_at'] = time();
            $return[$k] = $this->getModel()->create($data);
        }
        print_r($return);exit;
//        if(empty($request['business_uuid'])){
//            throw new \Exception('商户ID不能为空',201);
//        }
//        $employee = app('businessService')->getModel()->getByid($request['business_uuid']);
//
//        if(empty($employee)){
//            throw new \Exception('绑定的员工不存',201);
//        }

        $request['business_uuid'] =  1;

        $role = $this->getModel()->getByBusinessUuidRoleId($request['role_id'],$request['business_uuid']);

        if($role){
            return 1;
        }

        $data['role_id'] = $request['role_id'];
        $data['business_uuid'] = $request['business_uuid'];
        $data['create_at'] = time();
        $data['update_at'] = time();

        return $this->getModel()->create($data);
    }

    public function getByRoleId($role_id){
        return $this->getModel()->getByRoleId($role_id);
    }
}