<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;
use Illuminate\Support\Facades\Auth;
class OperationLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all(); //操作的内容
        $path = $request->path();  //操作的路由
        $method = $request->method();  //操作的方法
        $ip = $request->ip();  //操作的IP
        //$username = $request->username;  //操作人(要自己获取)
        self::writeLog('',$input,$path,$method,$ip);

        return $next($request);
    }
    public  function writeLog($username,$input,$path,$method,$ip){

        //$user = Users::where('username',$username)->first();
        $user=Auth::guard('api')->user();
        if($user) {
            $user_id = $user->user_id;
            $username = $user->username;
        }

        $log = new \App\Models\OperationLog();
        $log->setAttribute('user_id', $user_id);
        $log->setAttribute('username', $username);
        $log->setAttribute('path', $path);
        $log->setAttribute('method', $method);
        $log->setAttribute('ip', $ip);
        $log->setAttribute('input', json_encode($input, JSON_UNESCAPED_UNICODE));
        $log->save();
    }

}
