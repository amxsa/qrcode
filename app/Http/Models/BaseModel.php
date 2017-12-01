<?php

namespace App\Http\Models;

class BaseModel
{

	protected $table;

	public function __construct()
	{
		//
	}

	public function setTable($table = '')
	{
		return $this->table = trim($table);
	}

	public function getTable()
	{
		return $this->table;
	}
	/*
	 * 新增
	 */
	public function create($data = array()){
		if(!count($data)){return null;}
		return app('db')->table($this->table)->insertGetId($data);
	}

	/*
	 * 删除
	 */
	public function deleteById($id){
		if(!$id){ return null; }
		return app('db')->table($this->table)->where('id',$id)->delete();
	}

	/*
	 * 修改
	 */
	public function updateById($id , $data){
		if(!$id || !$data){ return null; }
		return app('db')->table($this->table)->where('id',$id)->update($data);
	}

	/*
	 * 获取全部
	 */
	public function getAll(){
		$sql = 'SELECT * FROM '.$this->table.' WHERE 1=1';
		$data = app('db')->select($sql);
		if(count($data)==0){ return null; }
		return $data;
	}

	/*
	 * 根据id获取
	 */
	public function getById($id){
		if(!$id){ return null; }
		return app('db')->table($this->table)->where('id',$id)->first();
	}

} 