<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;
use Illuminate\Support\Facades\Auth;

class webToken
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


            if (Auth::guard('api')->guest()) {
                return response()->json(['code' => 401, 'msg' => '未设置token']);
            }
            else {

                if( (Auth::guard('api')->user()->access_time+3600*2)-time()<0){
                    return response()->json(['code' => -1, 'msg' => '登录超时']);
                }else{

                    Users::where(['user_id' => Auth::guard('api')->user()->user_id])->update(['access_time' => time()]);
                }

            }

            return $next($request);

    }
}