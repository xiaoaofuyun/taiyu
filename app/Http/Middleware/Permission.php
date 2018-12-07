<?php

namespace App\Http\Middleware;

use App\Models\PermissionColumn;
use App\Models\UserRole;
use Closure;
use Illuminate\Support\Facades\Auth;
use Route;

class Permission
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

        if(Auth::guard('api')->user()->is_admin){
            return $response;
        }
        $routes = Route::getRoutes();

        $i = 0;
        foreach ($routes as $key => $route) {


            if (array_key_exists($route->uri, trans('route'))) {

                $rotes_arr[$i] = $route->uri;

                $i++;
            }
        }

        if (in_array($request->path(), $rotes_arr)) {

            $role_id = UserRole::where(['user_id' => Auth::guard('api')->user()->user_id])->get(['role_id'])->toArray();

            $permission_arr = [];

            foreach ($role_id as $r_id) {
                $permission = PermissionColumn::query()->where('role_id', $r_id['role_id'])->value('permission');

                if(!empty($permission))
                array_push($permission_arr, json_decode($permission)->sys);
            }


            foreach ($permission_arr as $pr) {
                if (in_array($request->path(), $pr)) {

                    return $response;
                }

            }
        }else{
            return $response;
        }

        //  print_r(json_encode($rotes_arr));

        return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.PERMISSION_ERROR'), 'result' => '']);
    }
}
