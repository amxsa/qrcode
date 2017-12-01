<?php


namespace App\Http\Models;


class ApplicationModel extends BaseModel
{
    protected $connection = 'application';
    protected $table = "application";


    /*
     * 根据应用id获取详情
     */
    public function getById($application_id)
    {
        if(!$application_id)
        {
            return null;
        }
        return app('db')
            ->connection($this->connection)
            ->table($this->table)
            ->where('id' , $application_id)
            ->first();
    }

    /*
     * 根据openid获取用户信息
     */
    public function getUserByOpenId($openid , $pre)
    {
        if(!$openid)
        {
            return null;
        }
        $table = 'application_open_'.$pre;
        return app('db')
            ->connection($this->connection)
            ->table($table)
            ->where('openid' , $openid)
            ->first();
    }
}