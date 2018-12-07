<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Role;
use App\Models\UserRole;
use App\User;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        if(Role::query()->where('company_id',$request->post('company_id'))->where('name',$request->post('name'))->count()>0)
            return response(['code' => trans('constants.UNIQUE_ERROR').$request->post('name'), 'msg' => '', 'result' => '']);
        $role = new Role();
        $role->company_id = $request->post('company_id');
         $role->user_id = 0;
        $role->name = $request->post('name');
        $role->describe = $request->post('describe');
        if ($role->save()) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }

    public function update(Request $request)
    {
        if (!$request->post('role_id') || !Role::find($request->post('role_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $role = Role::find($request->post('role_id'));
        $role->company_id = $request->post('company_id');
        //$role->user_id=$request->post('user_id');
        $role->name = $request->post('name');
        $role->describe = $request->post('describe');
        if ($role->update()) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }

    public function del(Request $request)
    {
        if (!$request->post('role_id') || !Role::find($request->post('role_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);

        if (Role::destroy($request->post('role_id'))) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }

    public function createur(Request $request)
    {
        $res = $request->all();

        if (count($res) == 2) {

            if (!$res['role_id'])
                return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
            $user_role = array();

            if(Role::query()->where('role_id',$res['role_id'])->value('company_id')<1)
                return;

            foreach ($res['user_id'] as $ur) {


                if((Users::query()->where('user_id',$ur)->value('company_id'))==Role::query()->where('role_id',$res['role_id'])->value('company_id')){
                    $ls['user_id'] = $ur;
                    $ls['role_id'] = $res['role_id'];
                    array_push($user_role, $ls);
                }

            }

            UserRole::query()->where('role_id', $res['role_id'])->delete();
            if (UserRole::insert($user_role)) {
                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
            return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
        }

    }
    public function listur(Request $request)
    {
        if (!$request->post('role_id'))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        return response(UserRole::query()->where('role_id', $request->post('role_id'))->get(['user_id']));
    }

    public function list(Request $request)
    {

        $data_type = Role::query()->where('company_id',$request->post('company_id'))->get()->toArray();
//        $data_type = Role::query()->where([])->get()->toArray();
//        foreach ($data_type as &$dt){
//
//
//
//            $dt['company_name']=Company::query()->where('company_id',$dt['company_id'])->value('name');
//
//        }

        return response(['code' => 1, 'msg' => 'SCCESS', 'result' => $data_type]);
    }

    public function ulist(Request $request)
    {


        $data_type = Role::query()->where(['user_id' => $request->post('user_id')])->get();
        return response(['code' => 1, 'msg' => 'SCCESS', 'result' => $data_type]);
    }

}