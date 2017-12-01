<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19 0019
 * Time: 16:39
 */

namespace App\Helpers;


/**
 * 新鉴权服务通讯类
 * Class NewMicroAuthPrivilege
 * @package App\Helpers
 */
class NewMicroAuthPrivilege
{
    private static $_instance;
    private $privilegeServerUrl;
    private $appKey;
    private $appSecret;
    private $appAccessToken;
    private $interfance;
    private $corpUuid;
    private $serviceUuid;
    private $serviceSecret;

    public function __construct()
    {
        if (env('APP_ENV') != 'prod') {
            $this->privilegeServerUrl = trim(env('ICE_AG_DEV', ''), ' /');//鉴权微服务地址
        //    $this->appKey = env('NEW_PRIVILEGE_APP_KEY_DEV', '');//鉴权微服务appKey
            $this->appSecret = '882b3c4d5e6f7a8b9c0d1a2b3c4d5e6f';//鉴权微服务secret
            $this->corpUuid = 'a8c58297436f433787725a94f780a3c9';//租户UUID
            $this->serviceUuid = '88a2b3c4d5e6f7a8b9c2';//服务UUID
            $this->corpSecret = '24152a8e4cc340b793c87b610946b81e'; //租户secret
       //     $this->serviceSecret = env('PRIVILEGE_SERVICE_SECRET_DEV');//服务SECRET
        } else {
            $this->privilegeServerUrl = trim(env('ICE_AG_PRO', ''), ' /');//鉴权微服务地址
          //  $this->appKey = env('PRIVILEGE_APP_KEY_PROD', '');//鉴权微服务appKey
            $this->appSecret = '882b3c4d5e6f7a8b9c0d1a2b3c4d5e6f';//鉴权微服务secret
            $this->corpUuid = 'a8c58297436f433787725a94f780a3c9';//租户UUID
            $this->serviceUuid = '88a2b3c4d5e6f7a8b9c2';//服务UUID
            $this->corpSecret = '24152a8e4cc340b793c87b610946b81e'; //租户secret
         //   $this->serviceSecret = env('PRIVILEGE_SERVICE_SECRET_PROD');//服务SECRET
        }

    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 获取应用授权
     * @return mixed
     */
    public function getAppAuth()
    {
//        $ts = time();
//        $signature = md5($this->serviceUuid . $ts . $this->appSecret);
//        print_r($ts);echo '</br>';
//        print_r($signature);exit;
        return app('cache')
            ->remember(
                'newMicro:newPrivilege:app:auth',
                20,
                function () {
                    $ts = time()*1000;
                    return app('iceService')->dispatch(
                        '/authms/auth/app',
                        [],
                        [
                            'corp_uuid' => $this->corpUuid,
                            'app_uuid' => $this->serviceUuid,
                            'signature' => md5($this->serviceUuid . $ts . $this->appSecret),
                            'timestamp' => $ts
                        ],
                        'POST'
                    );
                }
            );
    }

    /**
     * 获取本微服务的权限服务access_token
     * @return $this|bool
     * @throws \Exception
     */
    public function getAccessToken()
    {
        $cacheKey = 'newMicro:newPrivilege:app:auth';
        $token = app('cache')->get($cacheKey);
        if ($token['accessToken']) {
            return $token['accessToken'];
        }

        $accessToken = $this->flush()->getAppAuth();
//        app('cache')->put(
//            $cacheKey,
//            $accessToken['accessToken'],
//            ($accessToken['expireTime'] - time()) / 60 - 1
//        );
        if($accessToken['accessToken']){
            return $accessToken['accessToken'];
        }
        return false;
    }

    /**
     * ice2.0
     * 获取本微服务的权限服务access_token
     * @return $this|bool
     * @throws \Exception
     */
    public function newGetAccessToken()
    {
        $token = app('cache')
            ->remember(
                'new:ice:newMicro:newPrivilege:app:auth',
                20,
                function () {
                    $ts = time();
                    return NewICEService::getInstance()->dispatch(
                        '/authms/auth/app',
                        [],
                        [
                            'corp_uuid' => $this->corpUuid,
                            'app_uuid' => env('NEW_ICE_APPID'),
                            'signature' => md5(env('NEW_ICE_APPID') . $ts . env('NEW_ICE_TOKEN')),
                            'timestamp' => $ts
                        ],
                        'POST'
                    );
                }
            );

        if($token['accessToken']){
            return $token['accessToken'];
        }
        return false;
    }

//    /**
//     * 获取服务授权
//     * @return mixed
//     */
//    public function getServiceAuth()
//    {
//        return app('cache')
//            ->tags('newMicro:newPrivilege')
//            ->remember(
//                'newMicro:newPrivilege:service:auth',
//                cache_minute_random(),
//                function () {
//                    $ts = time();
//                    return app('iceService')->dispatch(
//                        '/authms/auth/service',
//                        [],
//                        [
//                            'service_uuid' => $this->serviceUuid,
//                            'signature' => md5($this->serviceUuid . $ts . $this->appSecret),
//                            'timestamp' => $ts
//                        ],
//                        'POST'
//                    );
//                }
//            );
//    }


//    /**
//     * 获取服务的access_token
//     * @return $this
//     */
//    public function getServiceAccessToken()
//    {
//        $cacheKey = 'newMicro:newAuth:service:token';
//        $token = app('cache')->get($cacheKey);
//
//        if ($token) {
//            return $token;
//        }
//
//        $accessToken = $this->flush()->getServiceAuth();
//        app('cache')->put(
//            $cacheKey,
//            $accessToken['accessToken'],
//            ($accessToken['expireTime'] - time()) / 60 - 1
//        );
//        return $accessToken['accessToken'];
//    }


    /**
     * 获取权限列表
     * @return bool
     * @throws \Exception
     */
    public function getAccessPriliveges()
    {
        return app('cache')
            ->tags('newMicro:newPrivilege')
            ->remember(
                'newMicro:newPrivilege:app:access:privileges:' . $this->getAccessToken(),
                cache_minute_random(),
                function () {
                    $privilege = app('iceService')->dispatch(
                        '/authms/app/privilege',
                        [
                            'service_token' => $this->appAccessToken,
                            'app_token' => $this->getAccessToken()
                        ],
                        [],
                        'GET'
                    );
                    return isset($privilege['privileges']) && $privilege['privileges']
                        ? $privilege['privileges']
                        : array();
                }
            );
    }

    public function authMicroRequest($accessToken, $interface = '' , $request_method = 'GET')
    {
        if (!$accessToken) {
            throw new \Exception('无法获取第三方调用方access_token', 9004);
        };

        $this->appAccessToken = $accessToken;
        $this->interfance = $interface;

        $privileges = $this->getAccessPriliveges();
        $flag = false;
        if(!empty($privileges))
        {
            foreach($privileges as $key=>$value)
            {
                if($value['url'] == $interface && $value['method'] == $request_method)
                {
                    $flag = true;
                }
            }
        }else{
            throw new \Exception('缺少接口访问权限: ' . $interface, 9005);
        }

        return $flag;
    }



    protected function flush()
    {
        app('cache')->tags('newMicro:newPrivilege')->flush();
        return $this;
    }
}