<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 16:26
 */
namespace App\Http\Services;

use App\Http\Models\PrivilegeModel;

class PrivilegeService extends BaseService{

    protected $cachePrefix = 'privilege';

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new PrivilegeModel();
        }

        return $this->model;
    }

    public function actionList($request){
        $employ = app('employeeService')->getByAccessToken($request['access_token']);
        if(empty($employ)){
            throw new \Exception('角色不存在',301);
        }
        if($employ->role_id != 1){
            $return = app('roleBusinessPrivilegeService')->getModel()->getTopByRole($employ->role_id);
            return $return;
        }else{
            return $this->getModel()->getTop();
        }

    }
    public function actionAdd($request){
        $data['name'] = $request['name'];
        $data['module'] = $request['module'];
        $data['parent_id'] = $request['parent_id'];
        $data['create_at'] = time();
        $data['update_at'] = time();
        return $this->getModel()->create($data);
    }
    public function actionView($request){
        $id = $request['id'];
        $model = $this->getModel()->getById($id);
        if(empty($model)){
            throw new \Exception('无此权限',101);
        }
        return $model;
    }
    public function actionEdit($request){
        $id = $request['id'];
        $model = $this->getModel()->getById($id);
        if(empty($model)){
            throw new \Exception('无该权限',101);
        }
        if($request['name']){
            $data['name'] = $request['name'];
        }
        if($request['module']){
            $data['module'] = $request['module'];
        }
        if($request['parent_id']){
            $data['parent_id'] = $request['parent_id'];
        }
        $data['update_at'] = time();
        return $this->getModel()->updateById($id,$data);
    }


    public function actionGetByParentId($request){
        if(empty($request['parent_id'])){
            throw new \Exception('父级权限不存在',101);
        }
        $return = $this->getModel()->getByParentId($request['parent_id']);
        return $return;
    }
}