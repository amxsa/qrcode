<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 16:32
 */

namespace App\Helpers;


class FinanceApi
{
    protected static $instance;
    protected $serviceUrl = '';

    public function __construct()
    {
        $this->serviceUrl = env('APP_ENV', 'local') == 'local'
            ? env('FINANCE_SERVER_DEV', '')
            : env('FINANCE_SERVER_PROD', '');
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * 处理请求结果(带data)
     * @param $result
     * @return bool|mixed
     */
    private function resolveResult($result)
    {
        app('myLog')->interestLog('金融接口请求返回：' . $result);

        $result = json_decode($result, true);

        if (isset($result['code']) && $result['code'] == 0) {
            return (isset($result['content']) && !empty($result['content'])) ? $result['content'] : false;

        } else {
            return false;
        }
    }


    // 查询用户金融账号余额
    public function queryClientAccount($pano, $cano)
    {
        $url = $this->serviceUrl . '/account/queryClientAccount';
        $access_token = app('microAuthPrivilege')->getAccessToken();
        $data = [
            'access_token' => $access_token,
            'pano' => $pano,
            'cano' => $cano
        ];
        $result = postCurl($url, $data, [], 'POST');

        return $this->resolveResult($result);
    }
}