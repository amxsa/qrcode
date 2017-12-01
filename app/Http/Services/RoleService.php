<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 9:30
 */
namespace App\Http\Services;

use App\Http\Models\RoleModel;

class RoleService extends BaseService{

    protected $cachePrefix = 'role';

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new RoleModel();
        }

        return $this->model;
    }

    public function actionList($request){
        $page_size = isset($request['page_size']) ? $request['page_size'] : 10;
        $request['name'] = isset($request['name']) ? $request['name'] : '';
        return $this->getModel()->getPageAll($request['name'],$page_size);
    }

    public function actionAdd($request){
        if(empty($request['name'])){
            throw new \Exception('角色名称不能为空',201);
        }
        if(empty($request['desc'])){
            throw new \Exception('角色描述不能为空',201);
        }
        $data['name'] = $request['name'];
        $data['desc'] = $request['desc'];
        $data['create_at'] = time();
        $data['update_at'] = time();
        return $this->getModel()->create($data);
    }

    public function actionSearch($request){
        if(empty($request['name'])){
            throw new \Exception('角色名称不能为空',201);
        }
        return $this->getModel()->search($request['name']);
    }
}