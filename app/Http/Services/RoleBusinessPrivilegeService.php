<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 14:19
 */
namespace App\Http\Services;

use App\Http\Models\RoleBusinessPrivilegeModel;

class RoleBusinessPrivilegeService extends BaseService{

    protected $cachePrefix = 'role_business_privilege';

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new RoleBusinessPrivilegeModel();
        }

        return $this->model;
    }

    public function actionList(){
        return $this->getModel()->getAll();
    }

    public function actionAdd($request){
        $employ = app('employeeService')->getByAccessToken($request['access_token']);

        if(empty($employ)){
            throw new \Exception('登录员工不存在',201);
        }

        $ids_array=explode(',',$request['ids']);

        if(empty($request['role_id'])){
            throw new \Exception('角色不能为空',201);
        }

        $role = app('roleService')->getModel()->getByRole($request['role_id']);
        if(empty($role)){
            throw new \Exception('角色不存在',201);
        }
        if(empty($request['business_uuid'])){
            throw new \Exception('商户ID不能为空',201);
        }
        $business = app('businessService')->getModel()->getBusinessByUuid($request['business_uuid']);

        if(empty($business)){
            throw new \Exception('绑定的商家不存在',201);
        }
        //删除之前数据库里面的数据
        $this->getModel()->deleteByUuid($request['business_uuid'],$request['role_id']);

        $return = '';
        foreach ($ids_array as $k=>$value){
            $data = [];
            $data['business_uuid'] = $request['business_uuid'];
            $data['role_id'] = $request['role_id'];
            $data['privilege_id'] = $value;
            $data['create_at'] = time();
            $data['update_at'] = time();
            $return[$k] = $this->getModel()->create($data);
        }
        return $return;
    }

    /*
     * 根据商户路由判断是否具有权限
     */
    public function getByBusinessPrivilege($business_uuid,$access_token,$privilege){
        $employee = app('employeeService')->getByAccessToken($access_token);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }
        if($employee->role_id !=1){
            $privilegeModel = app('privilegeService')->getModel()->getByModel($privilege);

            if(empty($privilegeModel)){
                throw new \Exception('该权限不存在',501);
            }
           
            $return = $this->getModel()->getByBusinessUuidRoleIdPrivilegeId($employee->role_id,$business_uuid,$privilegeModel->id);

            if(empty($return)){
                throw new \Exception('未被授权该操作',502);
            }
        }


    }

    /*
     * 根据路由角色判断是否具有权限
     */
    public function getByPrivilege($access_token,$privilege){

        $employee = app('employeeService')->getByAccessToken($access_token);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }

        if($employee->role_id != 1){
            $privilegeModel = app('privilegeService')->getModel()->getByModel($privilege);

            if(empty($privilegeModel)){
                throw new \Exception('该权限不存在',501);
            }

            $return = $this->getModel()->getByPrivilegeRole($employee->role_id,$privilegeModel->id);
            if(empty($return)){
                throw new \Exception('未被授权该操作',502);
            }
        }
    }

    /*
     * 根据角色获取左侧
     */
    public function getLeftByRole($request){
        $employee = app('employeeService')->getByAccessToken($request['access_token']);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }
        if($employee->role_id != 1){
            $privilegeModel = $this->getModel()->getTopByRole($employee->role_id);
        }else{
            $privilegeModel = app('privilegeService')->getModel()->getTop();
        }
        return $privilegeModel;
    }

    /*
     * 解除角色商户绑定
     */
    public function actionDel($request){
        $employee = app('employeeService')->getRoleByAccount($request['employee_account']);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }

        return $this->getModel()->deleteByUuid($request['uuid'],$employee->role_id);
    }

    /*
    * 解除角色商户绑定
    */
    public function actionBusinessByRole($request){
//        if(empty($request['role_id'])){
//            throw new \Exception('角色不存在',501);
//        }
//        return $this->getModel()->getBusinessByRoleId($request['role_id']);

        if(empty($request['role_id'])){
            throw new \Exception('角色不存在',501);
        }
        $businessUuid = $this->getModel()->getBusinessByRoleId($request['role_id']);
        if(empty($businessUuid)){
            throw new \Exception('当前无绑定商户',501);
        }
        foreach($businessUuid as $value){
            $data[] = $value->business_uuid;
        }
        if(empty($data)){
            return '';
        }else{
            return app('businessService')->getModel()->getBusinessUuidAndName($data);
        }

    }


    /*
    * 根据商户uuid角色id获取权限列表
    */
    public function actionPrivilegeByRoleAndUuid($request){
        if(empty($request['role_id'])){
            throw new \Exception('角色不存在',501);
        }
        if(empty($request['business_uuid'])){
            throw new \Exception('商户uuid不存在',501);
        }
        $return = $this->getModel()->getPrivilegeByRoleAndUuid($request['role_id'],$request['business_uuid']);
        $data = [];
        foreach ($return as $key => $value) {  
            if ($value->parent_id == 0) {
                $data[$key] = $value;
                foreach ($return as $item) {
                    if ($item->parent_id == $value->privilege_id) {
                        $data[$key]->children[] = $item;
                    }
                }
            }
        }
        return $data;
    }

    /*
     * 查看当前登录已绑定商户接口
     */
    public function actionBusinessList($request){
        $employee = app('employeeService')->getByAccessToken($request['access_token']);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }
        return $this->getModel()->getBusinessByRoleId($employee->role_id);
    }
}