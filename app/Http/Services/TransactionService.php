<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 11:06
 */
namespace App\Http\Services;

use App\Helpers\NewICEService;
use App\Helpers\NewMicroAuthPrivilege;
use App\Http\Models\PaymentModel;
use App\Http\Models\TransactionChildModel;
use App\Http\Models\TransactionModel;
use App\Helpers\ICEService;
use App\XLSXWriter\XLSXWriter;
use Overtrue\Wechat\Exception;

class TransactionService extends BaseService
{


    public function getModel($pre)
    {
        if (!$this->model) {
            $this->model = new TransactionModel($pre);
        }
        return $this->model;
    }

    public function actionList($request)
    {
        if($request['create_time']){
            $pre = date("Ym",$request['create_time']);
        }else {
            $pre = date("Ym");
        }
        //判断组织uuid是否为空
        if($request['org_uuid']){
            $uuid = $this->uuid($request['org_uuid']);
            $uuid =  str_replace("'","",$uuid );;
            $uuidData = explode(',',$uuid);
        }else{
            $uuidData = '';
        }
        $employee = app('employeeService')->getByAccessToken($request['access_token']);
        if (empty($employee)) {
            throw new \Exception('该用户不存在', 501);
        }
        $tranChild = new TransactionChildModel($pre);
        $data = '';
        if ($employee->role_id == 1) {
            $return = $tranChild->getTransactionAll('',$request,$uuidData);
//            if ($return) {
//                foreach ($return as $value) {
//                    $value->time_pay = date("Y-m-d h:i:s", $value->time_pay);
//                }
//            }
        } else {
            $businessIds = app('roleBusinessPrivilegeService')->getModel()->getBusinessByRoleId($employee->role_id);
            if ($businessIds) {
                foreach ($businessIds as $k=>$value){
                    $data[] = $value->business_uuid;
                }
                $return = $tranChild->getTransactionAll($data,$request,$uuidData);
            } else {
                return '';
            }
        }
        return $return;
    }

    public function actionView($request)
    {
        $colour_sn = $request['colour_sn'];
        $pre = substr($colour_sn, 0, 6);
        if (strlen($pre) != 6) {
            throw new \Exception('此订单号不存在！', 428);
        }
        $payment = new PaymentModel();

        //根据订单号获取提现详情
        $return = $this->getModel($pre)->getColourTradeNo($colour_sn,$pre);
        $payment_name = $payment->getNameByPaymentUuid($return->payment_uuid);
        if ($payment_name) {
            $return->payment_uuid = $payment_name->name;
        }

//        $return->time_start = date("Y-m-d h:i:s", $return->time_start);
//        $return->time_expire = date("Y-m-d h:i:s", $return->time_expire);
//        $return->time_create = date("Y-m-d h:i:s", $return->time_create);
//        $return->time_pay = date("Y-m-d h:i:s", $return->time_pay);
        return $return;
    }



    public function actionSearch($request)
    {
        $colour_sn = $request['colour_sn'];
        $pre = substr($colour_sn, 0, 6);
        if (strlen($pre) != 6) {
            throw new \Exception('此订单号不存在！', 428);
        }
        $payment = new PaymentModel();

        //根据订单号获取详情
        $return = $this->getModel($pre)->getColourTradeNo($colour_sn,$pre);
        $payment_name = $payment->getNameByPaymentUuid($return->payment_uuid);
        if ($payment_name) {
            $return->payment_uuid = $payment_name->name;
        }

//        $return->time_start = date("Y-m-d h:i:s", $return->time_start);
//        $return->time_expire = date("Y-m-d h:i:s", $return->time_expire);
//        $return->time_create = date("Y-m-d h:i:s", $return->time_create);
//        $return->time_pay = date("Y-m-d h:i:s", $return->time_pay);
        return $return;
    }


    public function actionNotify($request)
    {
        $colour_sn = $request['colour_sn'];
        $pre = substr($colour_sn, 0, 6);
        if (strlen($pre) != 6) {
            throw new \Exception('此订单号不存在！', 428);
        }
        //2：通知业务系统
        $callback = $this->callback($colour_sn);
        //3：通知分账系统
        $this->NotifySplit($colour_sn);
        //4：修改订单交易成功
        if ($callback) {
            $success_update = $this->updateSuccess($colour_sn, $callback);
            if ($success_update) {
                //5：返回success
                return 'SUCCESS';
            }
        }
    }
    /*
         * 支付回调
         * 根据订单通知业务系统支付成功
         */
    public function callback($colour_sn)
    {
        $pre = substr($colour_sn, 0, 6);
        if (strlen($pre) != 6) {
            throw new \Exception('此订单号不存在！', 428);
        }
        $order_info = $this->getModel($pre)->getByColourSn($colour_sn);

        $order_child_info = app('transactionChildService')->getModel($pre)->getByColourSn($colour_sn);
        if(!$order_info || !$order_child_info)
        {
            throw new \Exception('此订单号不存在！', 428);
        }

        $application = app('applicationService')->getById($order_info->application_id);
        if(!$application)
        {
            throw new \Exception('应用不存在', 425);
        }
        $secret = $application->secret;
        $notify_url = $order_info->notify_url;

        $payment  =  app('paymentService')->getByUuid($order_child_info->payment_uuid);

        $return_data =
            [
                'appID' => $order_info->application_id,
                'business_uuid' => $order_child_info->business_uuid,
                'device_info' => $order_info->device_info,
                'nonce_str' => StrtoUpper(kakatool_uuid(rand(0 ,20))),
                'open_id' => $order_info->open_id,
                'trade_type' => $order_info->trade_type,
                'total_fee' => bcmul( $order_info->total_fee, 100),
                'discount' => $order_child_info->discount,
                'payment_uuid' => $order_child_info->payment_uuid,
                'payment_name' => $payment->name,
                'colour_sn' => $order_info->colour_sn,
                'out_trade_no' => ''.$order_info->out_trade_no.'',
                'attach' => $order_info->attach,
                'time_pay' => $order_child_info->time_pay,
                'colour_trade_no' => $order_child_info->colour_trade_no,
                'trade_state' => $order_child_info->trade_state
            ];
        $sign = createSign($return_data , $secret );
        $return_data['signature'] = $sign;
        $result = postCurl($notify_url , $return_data);
        /*
         *通知业务系统日志记录
         */
        app('myLog')->interestLog(
            sprintf(
                '%s %s %s',
                '通知地址：'.$notify_url,
                '通知参数：'.json_encode(
                    $return_data,
                    JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_NUMERIC_CHECK
                ),
                '返回结果：'.json_encode(
                    $result,
                    JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_NUMERIC_CHECK
                )
            ),
            'out_trade_request'
        );
        if($result && $result == 'SUCCESS')
        {
            return $result;
        }else{
            return false;
        }
    }

    /*
   * 支付回调
  * 通知分账
  */
    public function NotifySplit($colour_sn)
    {
        $pre = substr($colour_sn, 0, 6);
        if (strlen($pre) != 6) {
            throw new \Exception('此订单号不存在！', 428);
        }

        $order_info = $this->getModel($pre)->getColourTradeNo($colour_sn,$pre);
        $order_child_info = app('transactionChildService')->getModel($pre)->getByColourSn($colour_sn);
        if(!$order_info || !$order_child_info)
        {
            throw new \Exception('此订单号不存在！', 428);
        }
        if($order_info->split_state == 2){
            return "SUCCESS";
        }
        $data =
            [
                'business_uuid' => $order_child_info->business_uuid,
                'attach' => $order_info->out_trade_no,
                'colour_sn' => $order_info->colour_sn,
                'real_total_fee' => $order_child_info->real_total_fee,
                'actual_pay_amount' => $order_child_info->actual_pay_amount,
                'course_code' => $order_info->course_code,
                'transfer' => 1,
                'request_channel' => 1,
                'access_token' => NewMicroAuthPrivilege::getInstance()->newGetAccessToken(),
            ];
        $results = NewICEService::getInstance()->dispatch(
            '/split/api/result',
            [],
            $data,
            'POST'
        );
        if($results)
        {
            $this->updateByColourSn($colour_sn , ['split_state' => 2]);
        }else{
            return $results->message;
        }
    }

    /*
     * 支付回调
     * 业务系统返回成功后修改订单为交易成功状态
     * colour_sn ：彩之云订单号
     * callback_results：业务系统返回信息
     */
    public function updateSuccess($colour_sn , $notify_msg)
    {
        $transaction_data =
            [
                'trade_state' => 3,
                'time_update' => time(),
                'notify_msg' => json_encode($notify_msg)
            ];
        return $this->updateByColourSn($colour_sn , $transaction_data);
    }

    /*
     * 根据订单修改订单子表信息
     */
    public function updateByColourSn($colour_sn , $data = [])
    {
        $order_child_info = app('transactionChildService')->getByColourSn($colour_sn);
        if(!$order_child_info)
        {
            throw new \Exception('此订单号不存在！', 428);
        }
        return app('transactionChildService')->updateByColourSn($colour_sn , $data);
    }


    public function actionExcel($request){

        if($request['create_time']){
            $pre = date("Ym",$request['create_time']);
        }else{
            $pre = date("Ym");
        }
        $tranChild = new TransactionChildModel($pre);

        //判断组织uuid是否为空
        if($request['org_uuid']){
            $uuid = $this->uuid($request['org_uuid']);
            $uuid =  str_replace("'","",$uuid );;
            $uuidData = explode(',',$uuid);
        }else{
            $uuidData = '';
        }
        $businessData = '';
        $employee = app('employeeService')->getByAccessToken($request['access_token']);
        if (empty($employee)) {
            throw new \Exception('该用户不存在', 501);
        }

        if($employee->role_id == 1){
            $data = $tranChild->getExcel('',$request,$uuidData);
        }else{
            $businessIds = app('roleBusinessPrivilegeService')->getModel()->getBusinessByRoleId($employee->role_id);
            if ($businessIds) {
                foreach ($businessIds as $k=>$value){
                    $businessData[] = $value->business_uuid;
                }
                $data = $tranChild->getExcel($businessData,$request,$uuidData);
            } else {
                return '';
            }
        }

        if(empty($data)){
            throw new \Exception('没有数据',3004);
        }

        $array = [];
        foreach ($data as $k=>$value){
            $array[$k]['e3_name'] = $value->e3_name;
            $array[$k]['e2_name'] = $value->e2_name;
            $array[$k]['e1_name'] = $value->e1_name;
            $array[$k]['shop_name'] = $value->shop_name;
            $array[$k]['colour_sn'] = $value->colour_sn;
            $array[$k]['real_total_fee'] = $value->real_total_fee;
            $array[$k]['payment_name'] = $value->payment_name;
            switch ($value->trade_state)
            {
                case 1:
                    $array[$k]['trade_state']  = "未支付";
                    break;
                case 2:
                    $array[$k]['trade_state']  = "已付款";
                    break;
                case 3:
                    $array[$k]['trade_state']  =  "交易成功";
                    break;
                case 4:
                    $array[$k]['trade_state']  = "已关闭";
                    break;
                case 5:
                    $array[$k]['trade_state']  = "已撤销";
                    break;
                case 6:
                    $array[$k]['trade_state']  = "用户支付中";
                    break;
                case 7:
                    $array[$k]['trade_state']  = "转入退款";
                    break;
                case 8:
                    $array[$k]['trade_state']  = "支付失败";
                    break;
                default:
                    $array[$k]['trade_state']  = "其他";
            };
            $array[$k]['mobile'] = $value->mobile;
            $array[$k]['time_pay'] = date('Y-m-d,H:i:s',$value->time_pay);
        }

        require_once(dirname(__FILE__) . '/../../XLSXWriter/XLSXWriter.class.php');
        $writer = new XLSXWriter();
        $filename = time().".xlsx";

        //设置 header，用于浏览器下载
        header('Content-disposition: attachment; filename="'.$writer::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        //每列的标题头
        $title = array (
            0 =>'事业部',
            1 =>'大区事业部',
            2 =>'小区名称',
            3 =>'商户名称',
            4 =>'彩之云订单号',
            5 =>'支付金额',
            6 =>'支付方式',
            7 =>'订单状态',
            8 =>'手机号码',
            9 =>'创单时间',
        );
        //工作簿名称
        $sheet1 = 'sheet1';
        $col_style = '';
        $rows = '';
        $temp = '';
        //对每列指定数据类型，对应单元格的数据类型
        foreach ($title as $key => $item){
            $col_style[] =  'string';
        }

        //设置列格式，suppress_row: 去掉会多出一行数据；widths: 指定每列宽度
        $writer->writeSheetHeader($sheet1, $col_style, ['suppress_row'=>true,'widths'=>[20,20,20,25,50,20,23,25,20]] );
        //写入第二行的数据，顺便指定样式
        $writer->writeSheetRow($sheet1, ['订单报表'],
            ['height'=>32,'font-size'=>20,'font-style'=>'bold','halign'=>'center','valign'=>'center']);

        /*设置标题头，指定样式*/
        $styles1 = array( 'font'=>'宋体','font-size'=>10,'font-style'=>'bold', 'fill'=>'#eee',
            'halign'=>'center', 'border'=>'left,right,top,bottom');
        $writer->writeSheetRow($sheet1, $title,$styles1);
        // 最后是数据，foreach写入
        foreach ($array as $value) {
            foreach ($value as $item) {  $temp[] = $item;}
            $rows[] = $temp;
            unset($temp);
        }
        $styles2 = ['height'=>16];
        foreach($rows as $row){
            $writer->writeSheetRow($sheet1, $row,$styles2);
        }

        //合并单元格，第一行的大标题需要合并单元格
        $writer->markMergedCell($sheet1, $start_row=0, $start_col=0, $end_row=0, $end_col=7);
        //输出文档
        $writer->writeToStdOut();
        exit(0);
    }

    public function actionOrg($request){
        $parent_uuid = $request['parent_uuid'];
        if(empty($parent_uuid)){
            $parent_uuid = '760d5ff3-136f-445f-b9df-f01d0943a9e0';
        }
        $data['pid'] = $parent_uuid;
        $data['size'] = 100;
        $results = iCEService::getInstance()->dispatch(
            '/org/page',
            $data,
            [],
            'GET'
        );
        return $results;
    }

    public function uuid($uuid){
        $data['pid'] = $uuid;
        $results = iCEService::getInstance()->dispatch(
            '/community/subs',
            $data,
            [],
            'GET'
        );
        return $results;
    }

    public function actionTest($mobile){
        $data['mobile'] = $mobile;
        try{
            iCEService::getInstance()->dispatch(
                '/newczy/customer/autoRegister',
                [],
                $data,
                'POST'
            );
        }catch(Exception $e){
            print_r($e);
        }


    }
}