<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 16:51
 */
namespace App\Http\Models;

class PrivilegeModel extends BaseModel{
    protected $table = 'privilege';
    public function __construct()
    {
    }
    public function update($id = 0, $data = array())
    {
        $data['update_at'] = time();
        return app('db')
            ->table($this->getTable())
            ->where('id', $id)
            ->update($data);
    }
    public function getTop(){

        $query = app('db')
            ->table($this->getTable())
            ->where('parent_id',0)
            ->select()
            ->get();
        return $query;
    }

    public function getByIds($ids){
        if($ids){
            $query = app('db')
                ->table($this->getTable())
                ->where('parent_id',0)
                ->whereIn('id',$ids)
                ->select()
                ->get();
            return $query;
        }else{
            return null;
        }
    }

    public function getByModel($module){

        if($module){
            $query = app('db')
                ->table($this->getTable())
                ->where('module',$module)
                ->select()
                ->first();
            return $query;
        }else{
            return null;
        }
    }

    public function getByParentId($parent_id){
        $data = app('db')
            //->connection($this->connection)
            ->table($this->getTable())
            ->where('parent_id',$parent_id)
            ->select()
            ->get();
        return $data;
    }

    
}