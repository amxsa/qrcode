<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 9:45
 */
namespace App\Http\Models;

class RoleModel extends BaseModel{
    protected $table = 'role';
    public function __construct()
    {
    }

    public function getPageAll($name,$page_size){
        $data = app('db')
            ->table($this->getTable())
            ->where('name','like',"%$name%")
            ->select()
            ->paginate($page_size);
        return $data;
    }

    public function getByRole($role_id){
        $data = app('db')
            ->table($this->getTable())
            ->where('id',$role_id)
            ->select()
            ->get();
        return $data;
    }

    public function search($name){
        $data = app('db')
            ->table($this->getTable())
            ->where('name','like',"%$name%")
            ->select()
            ->get();
        return $data;
    }

}