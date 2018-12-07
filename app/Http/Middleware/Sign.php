<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Sign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

       // echo sha1($request->post('username', '') .$request->post('password', '').date("Y-m-d",time()).config('app.encrypted'));exit;
 //echo ($request->post('username', '') .$request->post('password', '').date("Y-m-d",time()).config('app.encrypted'));exit;
        if (Auth::guard('api')->guest()) {
            if (!(sha1($request->post('username', '') . $request->post('password', '') . date("Y-m-d", time()) . config('app.encrypted')) == $request->post('sign'))) {
                return response()->json(['code' => '0', 'msg' => '签名错误']);
            }
        }
        else {

            if( (Auth::guard('api')->user()->access_time+3600*2)-time()<0){
                return response()->json(['code' => -1, 'msg' => '登录超时']);
            }else{

                Users::where(['user_id' => Auth::guard('api')->user()->user_id])->update(['access_time' => time()]);
            }

        }
        return $response;

    }
}
