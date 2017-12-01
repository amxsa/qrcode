<?php

namespace App\Http\Models;


class EmployeeModel extends BaseModel
{


    protected $table = 'employee';

    public function __construct()
    {
    }

    public function update($employeeId = 0, $data = array())
    {
        $data['update_at'] = time();
        return app('db')
            ->table($this->getTable())
            ->where('id', $employeeId)
            ->update($data);
    }

    public function readByEmpAccount($empAccount = '', $available = true)
    {
        $query = app('db')
            ->table($this->getTable())
            ->where('employee_account', $empAccount)
            ->where('deleted', '0');

        if ($available == true) {
            $query->where('status', '1');
        }
        return $query->get()->first();
    }

    public function readByAccessToken($accessToken = '', $available = true)
    {
        $query = app('db')
            ->table($this->getTable())
            ->where('access_token', $accessToken)
            ->where('deleted', '0');

        if ($available == true) {
            $query->where('status', '1');
        }
        return $query->get()->first();
    }

    public function duplicateUpdate($employee_account = '', $password,
                                    $realname = '', $mobile = '',
                                    $uuid = '', $orgId = '',
                                    $jobId = '', $jobName = '', $sex = '',
                                    $salt = '', $accessToken = '',
                                    $authFrom = '1')
    {
        $dateline = time();

        return app('db')->statement(
            'INSERT INTO ' . $this->getTable() . '(
				employee_account, password, realname, mobile, uuid, 
				org_id, job_id, job_name, sex, salt, access_token, auth_from,
				status, deleted, create_at, update_at
			)
			value(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) 
			ON DUPLICATE KEY 
			UPDATE password = ?, realname = ?, mobile = ?, uuid = ?, 
			org_id = ?, job_id = ?, job_name = ?, sex = ?,
			salt = ?, access_token = ?, auth_from = ?, update_at = ?',
            [
                $employee_account, $password, $realname, $mobile, $uuid,
                $orgId, $jobId, $jobName, $sex, $salt, $accessToken, $authFrom,
                1, 0, $dateline, $dateline,
                $password, $realname, $mobile, $uuid,
                $orgId, $jobId, $jobName, $sex,
                $salt, $accessToken, $authFrom, $dateline
            ]
        );
    }

    public function search($employee_account = '' , $name = '')
    {
        if(!$employee_account && !$name)
        {
            return null;
        }
        $query = app('db')->table($this->getTable());
        if($employee_account)
        {
            $query->where('employee_account' , 'like' ,'%'.$employee_account.'%');
        }
        if($name)
        {
            $query->where('realname' , 'like' ,'%'.$name.'%');
        }
        return $query->select('id' , 'realname' , 'employee_account')->get();
    }

    public function updateRoleIdByAccessToken($access_token,$role_id){
        $data['update_at'] = time();
        $data['role_id'] = $role_id;
        return app('db')
            ->table($this->getTable())
            ->where('access_token', $access_token)
            ->update($data);
    }

    public function updateRoleIdByAccount($employee_account,$role_id){
        $data['update_at'] = time();
        $data['role_id'] = $role_id;
        return app('db')
            ->table($this->getTable())
            ->where('employee_account', $employee_account)
            ->update($data);
    }


    public function updateByAccessToken($access_token, $data = array())
    {
        $data['update_at'] = time();
        return app('db')
            ->table($this->getTable())
            ->where('access_token', $access_token)
            ->update($data);
    }

    public function delByAccount($employee_account)
    {
        $data['update_at'] = time();
        $data['deleted'] = 1;
        return app('db')
            ->table($this->getTable())
            ->where('employee_account', $employee_account)
            ->update($data);
    }

    public function getRoleByAccount($employee_account){

        return app('db')
            ->table($this->getTable())
            ->where('employee_account', $employee_account)
            ->select()
            ->first();
    }

    public function getByRoleId($role_id){
        return app('db')
            ->table($this->getTable())
            ->where('role_id', $role_id)
            ->where('deleted',0)
            ->select()
            ->get();
    }
}