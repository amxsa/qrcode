<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 15:18
 */
namespace App\Http\Services;

use App\Http\Models\TransactionChildModel;

class TransactionChildService extends BaseService
{

    protected $model;


    public function __construct()
    {

    }

    public function getModel($pre)
    {
        $this->model = new TransactionChildModel($pre);
        return $this->model;
    }

    /*
     * 根据colour_sn查询订单详情
     */
    public function getByColourSn($colour_sn)
    {
        $transaction_pre =substr($colour_sn ,0,6);

        if(strlen($transaction_pre) != 6)
        {
            throw new \Exception('此订单号不存在！', 428);
        }
        return $this->getModel($transaction_pre)->getByColourSn($colour_sn);
    }

    /*
     * 根据订单修改订单信息
     */
    public function updateByColourSn($colour_sn , $data = [])
    {
        $transaction_pre =substr($colour_sn ,0,6);
        $order_info = $this->getModel($transaction_pre)->getByColourSn($colour_sn);
        if(!$order_info)
        {
            throw new \Exception('此订单号不存在！', 428);
        }
        return $this->getModel($transaction_pre)->updateBySn($colour_sn ,$data);
    }


}