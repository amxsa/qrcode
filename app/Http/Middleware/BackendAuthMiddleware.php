<?php
/**
 * Created by PhpStorm.
 * Date: 3/16/16
 * Time: 11:21 上午
 */

namespace App\Http\Middleware;

use App\Helpers\MicroAuthPrivilege;
use Closure;
use Illuminate\Http\Request as Request;

class BackendAuthMiddleware {

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
        

//       app('employeeService')->authAccessToken(
//			$request->input('access_token', '')
//		);
        return $next($request);
    }



}