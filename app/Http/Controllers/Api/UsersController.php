<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;

use App\Users;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Events\LoginEvent;
use Jenssegers\Agent\Agent;

use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{


    public function login(Request $request)
    {


            if (!empty($request->post('username', '')) && !empty($request->post('password', ''))) {

                $re = Users::query()->where(['username' => base64_decode($request->post('username', '')), 'password' => md5(base64_decode($request->post('password', '')). config('app.encrypted'))])->get();

                if ($re->count() > 0) {
                    $api_token = str_random(64);
                    Users::where(['user_id' => $re->toArray()[0]['user_id']])->update(['api_token' => $api_token, 'last_login' => time(),'access_time' => time()]);

                    //event(new LoginEvent(Auth::guard('api')->user(), new Agent(), \Request::getClientIp(), time()));
                    event(new LoginEvent($re->toArray()[0], new Agent(), \Request::getClientIp(), time()));
                    return response()->json(['code' => '1', 'api_token' => $api_token, 'result' => $re->toArray()]);
                } else {
                    return response()->json(['code' => '0', 'msg' => '用户名密码错误']);
                }

            } else {
                return response()->json(['code' => '0', 'msg' => '输入参数错误']);

            }

    }
    public function create(Request $request){
        if(Users::query()->where('username',base64_decode($request->post('username', '')))->count()==0){
            $user=new Users();
            $user->company_id=$request->post('company_id');
            $user->department_id=$request->post('department_id');
            $user->username=base64_decode($request->post('username', ''));
            $user->password=md5(base64_decode($request->post('password', '')). config('app.encrypted'));
            $user->name=$request->post('name');
            $user->phone=$request->post('phone');
            $user->email=$request->post('email');
            $user->order=$request->post('order',0);
            $user->status=$request->post('status',1);
            $user->api_token=str_random(64);
            if($user->save()){
                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
            return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
        }else{
            return response(['code' => trans('constants.NO_CODE_2'), 'msg' => '', 'result' => '']);
        }

    }
    public function update(Request $request){
        if (!$request->post('user_id')||!Users::find($request->post('user_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $user=Users::find($request->post('user_id'));
        $user->company_id=$request->post('company_id');
        $user->department_id=$request->post('department_id');
        $user->username=base64_decode($request->post('username', ''));
        $user->password=md5(base64_decode($request->post('password', '')). config('app.encrypted'));
        $user->name=$request->post('name');
        $user->phone=$request->post('phone');
        $user->email=$request->post('email');
        $user->order=$request->post('order',0);
        $user->status=$request->post('status',1);

        if($user->update()){
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }
    public function list(){
        $user=Users::query()->where('is_admin',0)->get();
        return response($user);
    }
    public function dlist(Request $request){
        if (!$request->post('department_id'))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $user=Users::query()->where('is_admin',0)->where('department_id',$request->post('department_id'))->get();
        return response($user);
    }
    public function del(Request $request)
    {
        if (!$request->post('user_id')||!Users::find($request->post('user_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        if (Users::destroy($request->post('user_id'))) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }
    public function repwd(Request $request)
    {
        if (!$request->post('user_id')||!Users::find($request->post('user_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $user=Users::find($request->post('user_id'));

        $user->password=md5(base64_decode($request->post('password', '')). config('app.encrypted'));

        if($user->update()){
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }

}