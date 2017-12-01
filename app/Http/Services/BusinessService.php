<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/19
 * Time: 10:03
 */
namespace App\Http\Services;

use App\Http\Models\BusinessModel;

class BusinessService extends BaseService{


    public function getModel()
    {
        if (!$this->model) {
            $this->model = new BusinessModel();
        }

        return $this->model;
    }

    public function actionList($request){
        $data = [];
        $employee = app('employeeService')->getByAccessToken($request['access_token']);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }
        if($employee->role_id == 1){
            $return =   $this->getModel()->getBusinessAll();
        }else{

            $businessIds = app('roleBusinessPrivilegeService')->getModel()->getBusinessByRoleId($employee->role_id);
            foreach ($businessIds as $k=>$value){
                $data[] = $value->business_uuid;
            }

            $return = $this->getModel()->getBusinessUuidS($data);
        }

        return $return;
    }

    public function actionStatusList($request){
        $data = [];
        $employee = app('employeeService')->getByAccessToken($request['access_token']);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }
        if($employee->role_id == 1){
            $return =   $this->getModel()->getBusinessStatusAll($request);
        }else{

            $businessIds = app('roleBusinessPrivilegeService')->getModel()->getBusinessByRoleId($employee->role_id);
            foreach ($businessIds as $k=>$value){
                $data[] = $value->business_uuid;
            }
            $return = $this->getModel()->getBusinessStatusUuidS($request,$data);
        }

        return $return;
    }

    public function actionView($request){
        //根据商户uuid获取商户信息
        $return  = $this->getModel()->getBusinessByUuid($request['business_uuid']);
        return $return;
    }

    public function actionSearch($request){
        if(empty($request['name'])){
            throw new \Exception('商户名称不存在',501);
        }
        $return = $this->getModel()->search($request['name']);

        return $return;
    }


    /*
     * 审核不通过接口
     */
    public function actionExamine($request){
        if(!$request){
            throw new \Exception('商户不存在',501);
        }
        $data['state'] = 3;  //审核不通过状态
        if($request['examine']){
            $data['examine'] = $request['examine'];
        }
        $return = $this->getModel()->updateStatus($request['business_uuid'],$data);
        return $return;
    }


    public function actionStatus($request){
        $data['state'] = $request['state'];
        $return = $this->getModel()->updateStatus($request['business_uuid'],$data);
        return $return;
    }

    public function actionGeneralBusiness(){
        $return = $this->getModel()->getGeneralBusinessList();
        return $return;
    }
}