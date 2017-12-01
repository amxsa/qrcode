<?php
/**
 * Created by PhpStorm.
 * User: Explorer
 * Date: 2017/9/19
 * Time: 23:07
 */
namespace App\Http\Services;

use App\Http\Models\PaymentModel;

class PaymentService extends BaseService
{

    protected $model;

    public function __construct()
    {
    }

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new PaymentModel();
        }
        return $this->model;
    }

    /*
     * 新增支付方式
     */
    public function create(
        $atid = '', $pano = '', $name = '' , $logo = '', $type,
        $discount = '' , $state = 1 , $creater = '' ,$parent_id = '')
    {
        if(empty($atid) || empty($pano) || empty($name) || empty($logo) || empty($type)
            || empty($discount) || empty($creater) || (empty($parent_id) && $parent_id != 0))
        {
            throw new \Exception('新增支付关键参数不能为空', 2011);
        }
        $data =
            [
                'uuid' => md5($atid.$pano.time().$name),
                'atid' => $atid,
                'name' => $name ,
                'pano' => $pano,
                'logo' => $logo,
                'discount' => $discount,
                'type' => $type,
                'state' => $state,
                'creater' => $creater,
                'parent_id' => $parent_id,
                'update_at' => time()
            ];
        return $this->getModel()->create($data);
    }
    
    /*
     * 修改支付方式
     */
    public function update($id , $data)
    {
        if(empty($data))
        {
            throw new \Exception('支付方式修改内容关键参数不能为空', 2011);
        }
        $payment = $this->getModel()->getById($id);
        if(!$payment)
        {
            throw new \Exception('该支付方式不存在！！！', 2004);
        }
        $data['update_at'] = time();
        return $this->getModel()->updateById($id , $data);
    }

    /*
     * 搜索支付方式列表
     */
    public function searchList($name = '' , $type = '' , $parent_id = '' , $page = 1 , $page_size = 10)
    {
        $result = $this->getModel()->getPaymentAll($name  , $type , $parent_id , $page  , $page_size);
        if($result)
        {
            foreach($result as $key => &$value)
            {
                $value->logo = strpos($value->logo,'http') ? $value->logo : env('WEB_SERVER').$value->logo;
                $value->state =  $value->state == 1 ? '正常':'禁用';
                $value->update_at = date("Y-m-d H:i",$value->update_at) ;
                //$value->type =  $value->type == 1 ? '饭票':'现金';
            }
        }
        return $result;
    }

    /*
     * 单个详情
     */
    public function getByUuid($uuid)
    {
        if(empty($uuid))
        {
            throw new \Exception('支付关键参数不能为空', 2011);
        }
        return $this->getModel()->getByPaymentUuid($uuid);
    }

    /*
     * 根据小区uuid获取支付方式列表
     */
    public function getByCommunityUuid($uuid)
    {
        if(empty($uuid))
        {
            throw new \Exception('支付关键参数不能为空', 2011);
        }
        return $this->getModel()->getByCommunityUuid($uuid);
    }

    /*
     * 根据小区uuid获取支付方式列表
     */
    public function getByPano($pano)
    {
        if(empty($pano))
        {
            throw new \Exception('支付关键参数不能为空', 2011);
        }
        return $this->getModel()->getByPano($pano);
    }

    /**
     * 获取默认的支付方式
     * @return mixed
     */
    public function getDefault()
    {
        return $this->getModel()->getDefault();
    }



}