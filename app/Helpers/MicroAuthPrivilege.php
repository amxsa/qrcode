<?php

namespace App\Helpers;

/**
 * 与鉴权微服务通讯类
 * Class PrivilegeService
 * @package App\Http\Services
 */
class MicroAuthPrivilege
{

	private static $_instance;
	private $privilegeServerUrl;
	private $appKey;
	private $appSecret;
	private $appAccessToken;
	private $interfance;

	public function __construct()
	{
		if (env('APP_ENV') != 'prod') {
			$this->privilegeServerUrl = trim(env('PRIVILEGE_SERVER_DEV', ''), ' /');//鉴权微服务地址
			$this->appKey = env('PRIVILEGE_APP_KEY_DEV', '');//鉴权微服务appKey
			$this->appSecret = env('PRIVILEGE_APP_SECRET_DEV', '');//鉴权微服务secret
		} else {
			$this->privilegeServerUrl = trim(env('PRIVILEGE_SERVER_PROD', ''), ' /');//鉴权微服务地址
			$this->appKey = env('PRIVILEGE_APP_KEY_PROD', '');//鉴权微服务appKey
			$this->appSecret = env('PRIVILEGE_APP_SECRET_PROD', '');//鉴权微服务secret
		}
	}

	public static function getInstance()
	{
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function getAppAuth()
	{

		return app('cache')
			->tags('micro:privilege')
			->remember(
				'micro:privilege:app:auth',
				cache_minute_random(),
				function () {
					$ts = time();
					return postCurlContent(
						sprintf('%s/app/auth', $this->privilegeServerUrl),
						[
							'appkey' => $this->appKey,
							'signature' => md5($this->appKey . $ts . $this->appSecret),
							'timestamp' => $ts
						]
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
		$cacheKey = env('PRIVILEGE_TOKEN_REDIS_KEY', 'micro:auth:token');
		$token = app('cache')->get($cacheKey);
		if ($token) {
			return $token;
		}

		$accessToken = $this->flush()->getAppAuth();

		app('cache')->put(
			$cacheKey,
			$accessToken['access_token'],
			($accessToken['expire'] - time()) / 60 - 1
		);
		return $accessToken['access_token'];
	}

	public function authMicroRequest($accessToken, $interface = '' , $request_method = 'GET')
	{
		if (env('APP_ENV') != 'prod' && env('APP_DEBUG') == true) {
			//return true;
		}
		if (!$accessToken) {
			throw new \Exception('无法获取第三方调用方access_token', 9004);
		};

		$this->appAccessToken = $accessToken;
		$this->interfance = $interface;

		$privilegesFull = $this->getAccessFullPriliveges();
		$flag = false;
		if(!empty($privilegesFull))
		{
			foreach($privilegesFull as $key=>$value)
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


	public function getAccessAppListPrivilege()
	{
		return app('cache')
			->tags('micro:privilege')
			->remember(
				'micro:privilege:app:listprivilege',
				cache_minute_random(),
				function () {
					return postCurlContent(
						sprintf('%s/app/listprivilege', $this->privilegeServerUrl),
						array(
							'token' => $this->appAccessToken,
							'access_token' => $this->getAccessToken()
						)
					);
				}
			);
	}

	/**
	 * 获取权限列表
	 * @return bool
	 * @throws \Exception
	 */
	public function getAccessPriliveges()
	{
		return app('cache')
        ->tags('micro:privilege')
        ->remember(
            'micro:privilege:app:access:privileges:' . $this->appAccessToken,
            cache_minute_random(),
            function () {
                $privilege = $this->getAccessAppListPrivilege();

                return isset($privilege['privileges']) && $privilege['privileges']
                    ? $privilege['privileges']
                    : array();
            }
        );
	}

	public function getAccessFullPriliveges()
	{
		return app('cache')
			->tags('micro:privilege')
			->remember(
				'micro:privilege:app:access:privileges:full:' . $this->appAccessToken,
				cache_minute_random(),
				function () {
					$privilege = $this->getAccessAppListPrivilege();

					return isset($privilege['privileges_full']) && $privilege['privileges_full']
						? $privilege['privileges_full']
						: 0;
				}
			);
	}

	public function getAccessAppId()
	{
		if (env('APP_ENV') != 'prod' && env('APP_DEBUG') == true) {
			return 17;
		}
		return app('cache')
			->tags('micro:privilege')
			->remember(
				'micro:privilege:app:access:appId:' . $this->appAccessToken,
				cache_minute_random(),
				function () {
					$privilege = $this->getAccessAppListPrivilege();

					return isset($privilege['appId']) && $privilege['appId']
						? $privilege['appId']
						: 0;
				}
			);
	}

	public function getAccessAppName()
	{
		return app('cache')
			->tags('micro:privilege')
			->remember(
				'micro:privilege:app:access:appName:' . $this->appAccessToken,
				cache_minute_random(),
				function () {
					$privilege = $this->getAccessAppListPrivilege();

					return isset($privilege['app_name']) && $privilege['app_name']
						? $privilege['app_name']
						: 0;
				}
			);
	}

	public function getAccessAppKey()
	{
		return app('cache')
			->tags('micro:privilege')
			->remember(
				'micro:privilege:app:access:appKey:' . $this->appAccessToken,
				cache_minute_random(),
				function () {
					$privilege = $this->getAccessAppListPrivilege();

					return isset($privilege['app_key']) && $privilege['app_key']
						? $privilege['app_key']
						: 0;
				}
			);
	}

	protected function flush()
	{
		app('cache')->tags('micro:privilege')->flush();
		return $this;
	}
}

