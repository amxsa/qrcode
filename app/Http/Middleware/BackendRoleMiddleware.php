<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 10:39
 */
namespace App\Http\Middleware;

use App\Helpers\MicroAuthPrivilege;
use Closure;
use Illuminate\Http\Request as Request;

class BackendRoleMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app('log')->debug('request url:'.PHP_EOL.$request->path());
        app('log')->debug('request param:'.json_encode($request->all()).PHP_EOL);

        $router = explode("?", $request->getRequestUri())[0];

        app('employeeService')->authAccessToken(
			$request->input('access_token', '')
		);
       app('roleBusinessPrivilegeService')->getByBusinessPrivilege(
           $request->input('business_uuid', ''),
           $request->input('access_token',''),
           $router
		);
        return $next($request);
    }



}