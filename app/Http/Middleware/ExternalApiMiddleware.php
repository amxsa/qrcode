<?php
/**
 * Created by PhpStorm.
 * 标准对外提供服务API中间件
 * Time: 11:21 上午
 */

namespace App\Http\Middleware;

use App\Helpers\MyLog;
use Closure;
use Illuminate\Http\Request as Request;

class ExternalApiMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app('myLog')->interestLog(
            sprintf(
                '%s %s %s',
                $request->method(),
                $request->path(),
                json_encode(
                    $request->all(),
                    JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_NUMERIC_CHECK
                )
            ),
            'admin_business'
        );

        //return $next($request);
        $exception = $this->authRequest($request);


        if($exception){
            return response(array('code'=>423,'message'=>'签名认证失败！','content'=>'','contentEncrypt'=>''));
        }
        return $next($request);
    }

    /**
     * 判断第三方权限
     * @param Request $request
     * @return \Exception|null
     */
    private function authRequest(Request $request){
        $sign_type = $request->get('sign_type' , 'MD5');
        $signature = $request->get('signature' , '');
        $data = $request->all();
        //判断应用是否存在并获取secret值
        $application = app('applicationService')->getById($request->get('application_id' , ''));
        if(!$application)
        {
            return true;
        }
        $secret = $application->secret;
        $sign = createSign($data , $secret ,$sign_type);
        if($sign != $signature)
        {
            return true;
        }
    }

} 