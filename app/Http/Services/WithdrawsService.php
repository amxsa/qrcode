<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31
 * Time: 15:29
 */
namespace App\Http\Services;

use App\Http\Models\WithdrawsModel;

class WithdrawsService extends BaseService{


    public function getModel()
    {
        if (!$this->model) {
            $this->model = new WithdrawsModel();
        }
        return $this->model;
    }

    public function actionList($request){
        $employee = app('employeeService')->getByAccessToken($request['access_token']);
        if(empty($employee)){
            throw new \Exception('该用户不存在',501);
        }
        if($employee->role_id == 1){
            $return =  $this->getModel()->getWithdrawsAll($request);
            foreach($return as $k=>$value){
                $value->crate_at = date("Y-m-d h:i:s", $value->crate_at);
                $value->update_at = date("Y-m-d h:i:s", $value->update_at);
            }
        }else{
            $businessIds = app('roleBusinessPrivilegeService')->getModel()->getBusinessByRoleId($employee->role_id);
            foreach ($businessIds as $k=>$value){
                $data[] = $value->business_uuid;
            }
            $return = $this->getModel()->getByWithdrawsUuid($request,$data);
        }
        return $return;
    }

    public function actionView($request){
        $payNo = $request['pay_no'];
        //根据订单号获取提现详情
        $return = $this->getModel()->getByPayNo($payNo);
        return $return;
    }

    /*
     * 提现搜索接口
     */
    public function actionSearch($request){
        $return = $this->getModel()->search($request);
        return $return;
    }

    /*
     * 提现通知
     */
    public function actionNotify($request){
        $payNo = $request['pay_no'];
        $business_uuid = $request['business_uuid'];
        $withdraws = $this->getModel()->getByPaymentNoAndBusinessUuid($payNo,$business_uuid);
        if (env('APP_ENV') == 'prod') {
            $url = "http://business.colourlife.com/business/notify";

        }else{
            $url = "http://business-czytest.colourlife.com/business/notify";
        }
        if(isset($withdraws->notify_info)){
            $info = json_decode($withdraws->notify_info);
            $data = [
                'notifyTime' => $info->notifyTime,
                'createTime' => $info->createTime,
                'tradeStatus' => $info->tradeStatus,
                'returnMessage' => $info->returnMessage,
                'sign' => $info->sign,
                'amount' => $info->amount,
                'charge' => $info->charge,
                'outSellerId' => $info->outSellerId,
                'sellerId' => $info->sellerId, 
                'outPaymentNo' => $info->outPaymentNo,
                'merchantNo' => $info->merchantNo,
                'payTradeNo' => $info->payTradeNo,
                'paymentNo' => $info->paymentNo
            ];
            $return = postCurl($url,$data);
            return $return;
        }else{
            return "FAIL";
        }
    }
}