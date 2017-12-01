<?php

namespace App\Http\Services;
use App\Http\Models\ApplicationModel;


class ApplicationService extends BaseService
{
    public function getModel()
    {
        if (!$this->model) {
            $this->model = new ApplicationModel();
        }
        return $this->model;
    }

    /*
     * 根据应用id获取应用信息
     */
    public function getById($application_id)
    {
        if(empty($application_id))
        {
            throw new \Exception('关键参数应用id不能为空', 2011);
        }
        return $this->getModel()->getById($application_id);
    }

    /*
     * 根据openid获取用户id
     */
    public function getUserByOpenId( $application_id ,$openid)
    {
        $application = $this->getById($application_id);
        if(!$application)
        {
            throw new \Exception('application_id不存在或错误', 425);
        }
        $pre = $application->table_pre;
        $open_user = $this->getModel()->getUserByOpenId($openid , $pre);
        if(!$open_user)
        {
            throw new \Exception('openid用户不存在', 426);
        }
        return $open_user;
    }

}