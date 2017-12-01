<?php

namespace App\Http\Services;


use App\Http\Models\EmployeeModel;

class EmployeeService extends BaseService
{


	protected $cachePrefix = 'employee';
	protected $currentemployee_account;

	public function __construct()
	{
	}

	public function getCacheKey(...$args)
	{
		$key = parent::getCacheKey(...$args);

		return env('APP_ENV') == 'local' ? $key : md5($key);
	}

	public function getModel()
	{
		if (!$this->model) {
			$this->model = new EmployeeModel();
		}

		return $this->model;
	}

	/*
     * 员工登录验证
     */
	public function login($employee_account, $password)
	{
		$result = app('iCEService')->dispatch(
			'account/login',
			[],
			[
				'username' => $employee_account,
				'password' => $password
			],
			'POST'
		);

		if (!$result) {
			throw new \Exception('员工账号不存在', 1003);
		}

		if (isset($result['disable']) && intval($result['disable']) == 1) {
			throw new \Exception('账号已被禁用,请联系管理员重新开通', 1004);
		}

		// 是否禁用
		$account = $this->getModel()->readByEmpAccount($employee_account);
		if ($account && isset($account->status) && $account->status == '0') {
			throw new \Exception('您未被授权登录本系统', 1003);
		}

		$accessToken = $this->duplicateUpdate(
			$employee_account,
			$password,
			$result,
			'1'
		);

		$result['access_token'] = $accessToken;
		$result['avatar'] = sprintf(
			'http://iceapi.colourlife.com:8686/avatar?uid=%s',
			$result['employeeAccount']
		);
		$result['redirect'] = env('WEB_SERVER').'index.html';
		return $result;
	}

	public function loginFromPortal($employee_account, $accessToken)
	{
		$result = app('iCEService')->dispatch(
			'account/mix',
			[],
			[],
			'GET',
			[
				'access-token: ' . $accessToken
			]
		);

		if (!$result || !isset($result['username'])) {
			throw new \Exception('您未被授权登录本系统', 1003);
		}

		if ($result['username'] != $employee_account) {
			throw new \Exception('无效的身份令牌', 1005);
		}

		$systemAdmin = $this->checkIfIsSystemAdmin($employee_account);
		$fanpiaoAdmin = $this->checkIfIsFanpiaoAdmin($employee_account);
		if (!$systemAdmin && !$fanpiaoAdmin) {
			throw new \Exception('您未被授权登录本系统', 1003);
		}

		// 是否禁用
		$account = $this->getModel()->readByEmpAccount($employee_account);
		if ($account && isset($account['status']) && $account['status'] == '0') {
			throw new \Exception('您未被授权登录本系统', 1003);
		}


		$result = app('iCEService')->dispatch(
			'account',
			[
				'username' => $employee_account,
			],
			[],
			'GET'
		);

		$accessToken = $this->duplicateUpdate(
			$employee_account,
			$accessToken,
			$result,
			'2'
		);

		$result['access_token'] = $accessToken;
		$result['avatar'] = sprintf(
			'http://iceapi.colourlife.com:8686/avatar?uid=%s',
			$result['employee_account']
		);
		$result['systemAdmin'] = $systemAdmin ? '1' : '0';
		$result['fanpiaoAdmin'] = $fanpiaoAdmin ? '1' : '0';

		return $result;
	}

	/*
     * access_token验证
     */
	public function authAccessToken($accessToken = '')
	{
		$employee = $this->getByAccessToken($accessToken, true);
		if (!$employee) {
			throw new \Exception('员工身份校验失败', 1003);
		}

		if ($accessToken != $this->getAccessToken(
				$employee->employee_account,
				$employee->password,
				$employee->salt
			)
		) {
			throw new \Exception('员工身份校验失败', 1003);
		}

		if (abs(time() - $employee->update_at) > 7200) {
			throw new \Exception('登录超时', 1006);
		}
	}


	/*
     * 根据access_token获取用户信息
     */
	public function getByAccessToken($accessToken = '', $flush = false)
	{
		if (!$accessToken) {
			$accessToken = $this->getQueryAccessToken();
		}

		if (!$accessToken) {
			throw new \Exception('缺少认证参数', 9000);
		}

		$tokenKey = $this->getCacheKey('token', $accessToken);
		if ($flush == true) {
			app('cache')->forget($tokenKey);
		}
		$cacheMinute = cache_minute_random();
		$account = app('cache')->remember(
			$tokenKey,
			$cacheMinute,
			function () use ($accessToken) {
				$account = $this->getModel()->readByAccessToken($accessToken, true);
				if (!$account || !isset($account->employee_account)) {
					throw new \Exception('账号不存在', 9001);
				}

				return $account;
			}
		);

		if ($account && isset($account->employee_account)) {
			$accountKey = $this->getCacheKey('account', $account->employee_account);
			if ($flush) {
				app('cache')->forget($accountKey);
			}
			app('cache')->put(
				$accountKey,
				$account,
				$cacheMinute
			);
		}

		if ($account && isset($account->uuid)) {
			$accountKey = $this->getCacheKey('uuid', $account->uuid);
			if ($flush) {
				app('cache')->forget($accountKey);
			}
			app('cache')->put(
				$accountKey,
				$account,
				$cacheMinute
			);
		}
		return $account;
	}

	public function getByUuid($uuid = '', $flush = false)
	{
		if (!$uuid) {
			throw new \Exception('账号不存在', 9011);
		}

		$cacheMinute = cache_minute_random();
		$cacheKey = $this->getCacheKey('uuid', $uuid);
		$account = app('cache')->remember(
			$cacheKey,
			$cacheMinute,
			function () use ($uuid) {
				$account = app('iCEService')->dispatch(
					'account',
					[
						'uid' => $uuid,
					],
					[],
					'GET'
				);

				if (!$account || !isset($account['uuid'])) {
					throw new \Exception('账号不存在', 9001);
				}

				if (!$account
					|| !isset($account['orgId'])
					|| !$account['orgId']
				) {
					throw new \Exception('用户未关联组织结构', 9002);
				}

				return $account;
			}
		);


		$accountKey = $this->getCacheKey('account', $account['employee_account']);
		if ($flush) {
			app('cache')->forget($accountKey);
		}
		app('cache')->put(
			$accountKey,
			$account,
			$cacheMinute
		);

		return $account;
	}

	public function getRealnameByUuid($uuid = '', $flush = false)
	{
		try {
			$employee = $this->getByUuid($uuid, $flush);
		} catch (\Exception $e) {
			$employee = [];
		}

		return isset($employee['realname']) ? $employee['realname'] : '';
	}

	public function getemployee_accountJurisdiction()
	{
		$employee = $this->getByAccessToken();
		$employee_account = $employee['employee_account'];
		$orgId = $employee['orgId'];

		return app('cache')->remember(
			$this->getCacheKey('jurisdiction', $employee_account),
			cache_minute_random(),
			function () use ($employee_account, $orgId) {
				$orgs = [];
				$pids = [];

				if ($orgId) {
					$pids[] = $orgId;
				}

				try {
					$result = app('microSDK')->dispatch(
						'v1/jurisdiction/account',
						array(
							'username' => $employee_account,
							'app_code' => env('EMPLOYEE_ACCOUNT_JURISDICTION_CODE')
						),
						array(),
						'get'
					);

					if ($result && is_array($result)) {
						foreach ($result as $org) {
							$uuid = $org['org_id'];

							// 如果分配的权限已经包含在账号所属组织结构，则跳过
							if (in_array($uuid, $orgs)
								|| in_array($uuid, $pids)
							) {
								continue;
							}

							if ($org['is_all'] == 1) {
								$pids[] = $org['org_id'];
							} else {
								$orgs[] = $org['org_id'];
							}
						}
					}
				} catch (\Exception $e) {
				}

				if ($pids) {
					$subs = $this->getemployee_accountOrgSub(implode(',', $pids));

					if ($subs) {
						$orgs = array_merge(
							$orgs,
							$subs
						);
					}
				}
				return $orgs;
			}
		);
	}

	/*
     * 获取当前请求access_token
     */
	private function getQueryAccessToken()
	{
		return isset($_GET['access_token']) ? $_GET['access_token'] : '';
	}

	/*
     * 插入或更新员工数据
     */
	private function duplicateUpdate($employee_account = '', $password = '',
									 $data = [], $authFrom = 1, $salt = '')
	{
		if (!$salt) {
			$salt = kakatool_uuid('-');
		}
		$accessToken = $this->getAccessToken(
			$employee_account,
			$password,
			$salt
		);
		$this->getModel()->duplicateUpdate(
			$employee_account,
			$password,
			$data['realname'],
			$data['mobile'],
			$data['uuid'],
			$data['orgId'],
			$data['jobId'],
			$data['jobName'],
			$data['sex'],
			$salt,
			$accessToken,
			$authFrom
		);

		return $accessToken;
	}

	/*
     * 生成access_token
     */
	private function getAccessToken($employee_account = '', $password = '', $salt = '')
	{
		return strtoupper(md5($employee_account . $password . $salt));
	}

	private function getemployee_accountOrgSub($pid = '')
	{
		return app('cache')->remember(
			$this->getCacheKey('jurisdiction', 'org', 'sub', $pid),
			cache_minute_random(),
			function () use ($pid) {
				try {
					$result = app('microSDK')->dispatch(
						'v1/org/subs',
						array(
							'pid' => (string)$pid
						),
						array(),
						'get'
					);
					return explode("','", substr($result, 1, -1));
				} catch (\Exception $e) {
				}
			}
		);
	}


	/*
     * 获取当前登录员工信息
     */
	public function getCurrentemployee_account($key = '')
	{
		if (!$this->currentemployee_account) {
			$this->currentemployee_account = $this->getByAccessToken();
		}

		if ($key) {
			return isset($this->currentemployee_account->$key)
				? $this->currentemployee_account->$key
				: '';
		} else {
			return $this->currentemployee_account;
		}
	}

	public function getCurrentemployee_accountParentOrgGroupUUID($forSearch = false)
	{
		$account = $this->getCurrentemployee_account();
		$group = $parent = app('orgService')->readByUUIDAndCreate(
			$account['orgId']
		);

		$groupId = $group['group_id'];

		if ($forSearch == true || $group['group_id'] == env('COLOURLIFE_ORG_TOP_UUID')) {
			$groupId = '';
		}

		return $groupId;
	}

	/*
     * 退出登录
     */
	public function logout()
	{
		$employee = $this->getByAccessToken();
		if ($employee && isset($employee->employee_account)) {
			$this->getModel()->update($employee->id, [
				'access_token' => ''
			]);
			app('cache')->forget($this->getCacheKey('token', $employee->access_token));
		}
		return true;
	}

	/*
     *判断员工是否有对应应用的权限,返回应用列表
     */
	public function checkApplication($employee_id = '' , $employee_account = '')
	{
		if(empty($employee_id) && empty($employee_account))
		{
			throw new \Exception('缺少必填参数', 2000);
		}
		return app('applicationEmployeeRelation')->readByEmployee(
			$employee_id , $employee_account);
	}

	/*
     * 搜索员工
     */
	public function search($employee_account = '', $name = '')
	{
		if(empty($employee_account) && empty($name))
		{
			throw new \Exception('关键参数不能为空($employee_account&&$name)', 2000);
		}
		return $this->getModel()->search($employee_account , $name);
	}

	/*
     * 搜索员工
     */
	public function apiSearch($name)
	{
		$result = app('iCEService')->dispatch(
			'account/search',
			array(),
			array(
				'keyword' => $name,
			),
			'post'
		);
		return $result;
	}

	/*
     * 根据id获取员工信息
     */
	public function getById($id)
	{
		if(empty($id))
		{
			throw new \Exception('关键参数不能为空($id)', 2000);
		}
		return $this->getModel()->getById($id);
	}

	/*
     * 新增员工记录
     */
	public function addEmployee($employee_account)
	{
		if(!$employee_account)
		{
			throw new \Exception('缺少必填参数(employee_account)', 2000);
		}
		$result = app('iCEService')->dispatch(
			'account',
			[
				'username' => $employee_account,
			],
			[],
			'GET'
		);
		if (!$result) {
			throw new \Exception('员工账号不存在', 1003);
		}

		if (isset($result['disable']) && intval($result['disable']) == 1) {
			throw new \Exception('账号已被禁用,请联系管理员重新开通', 1004);
		}
		$accessToken = $this->duplicateUpdate(
			$employee_account,
			$accessToken = '',
			$result,
			'4'
		);
		return $this->getByAccessToken($accessToken);

	}

	public function editRoleId($request){
		$employ = $this->getByAccessToken($request['access_token']);
		if(empty($employ) && $employ->role_id != 1){
			throw new \Exception('员工不存在,或权限不够', 1004);
		}
		if(!$request['employee_account']){
			throw new \Exception('员工账号不存在', 1003);
		}
		if($request['role_id'] != 0){
//			$role = app('roleService')->getModel()->getById($request['role_id']);
//			if(empty($role)){
				throw new \Exception('权限不存在', 1004);
//			}
		}
		return $this->getModel()->updateRoleIdByAccount($request['employee_account'],$request['role_id']);

	}

	public function updateStatus($request){
		$employ = $this->getByAccessToken($request['access_token']);
		if(empty($employ)){
			throw new \Exception('员工不存在', 1004);
		}
		$data['status'] = 0;
		return $this->getModel()->updateByAccessToken($request['access_token'],$data);

	}

	/*
     * 新增员工记录
     */
	public function addEmployeeRole($request)
	{
		if(!$request['employee_account'])
		{
			throw new \Exception('缺少必填参数(employee_account)', 2000);
		}
		$employee = $this->getModel()->getRoleByAccount($request['employee_account']);

		if(!$request['role_id'])
		{
			$request['role_id'] = 2;
		}
		if($employee){
			if(empty($employee->role_id)){
				return $this->getModel()->updateRoleIdByAccount($request['employee_account'],$request['role_id']);
			}else{
				throw new \Exception('该员工已绑定角色，请删除后重新绑定',2003);
			}
		}
		$result = app('iCEService')->dispatch(
			'account',
			[
				'username' => $request['employee_account'],
			],
			[],
			'GET'
		);
		if (!$result) {
			throw new \Exception('员工账号不存在', 1003);
		}

		if (isset($result['disable']) && intval($result['disable']) == 1) {
			throw new \Exception('账号已被禁用,请联系管理员重新开通', 1004);
		}
		$accessToken = $this->addEmp(
			$request['employee_account'],
			$accessToken = '',
			$result,
			'4',
			$salt='',
			$request['role_id']
		);
		return $this->getByAccessToken($accessToken);

	}

	public function addEmp($employee_account = '', $password = '', $data = [], $authFrom = 1, $salt = '',$role){
		if (!$salt) {
			$salt = kakatool_uuid('-');
		}
		$accessToken = $this->getAccessToken(
			$employee_account,
			$password,
			$salt
		);
		$dataArray = [
			'employee_account'=>$employee_account,
			'password'=>$password,
			'realname'=>$data['realname'],
			'mobile'=>$data['mobile'],
			'uuid'=>$data['uuid'],
			'org_id'=>$data['orgId'],
			'job_id'=>$data['jobId'],
			'job_name'=>$data['jobName'],
			'sex'=>$data['sex'],
			'salt'=>$salt,
			'access_token'=>$accessToken,
			'auth_from'=>$authFrom,
			'role_id'=>$role,
			'create_at'=>time(),
			'update_at'=>time(),
			'status'=>1,
			'deleted'=>0
		];
		$this->getModel()->create($dataArray);
		return $accessToken;
	}

	public function actionDel($request){
		if(!$request['employee_account']){
			throw new \Exception('员工账号不存在', 1003);
		}
		return $this->getModel()->delByAccount($request['employee_account']);
	}

	public function actionGetByRoleId($request){
		if(!$request['role_id']){
			throw new \Exception('角色不存在', 1003);
		}
		return $this->getModel()->getByRoleId($request['role_id']);
	}
}