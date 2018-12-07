<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;


use App\Models\Btns;

use Illuminate\Routing\Controller;

use App\Models\PermissionColumn;
use App\Models\UserRole;

use Illuminate\Support\Facades\Auth;


class BtnsController extends Controller
{
    public function list()
    {
        try{
           $data_type= Btns::all()->toArray();


            $role_id = UserRole::where(['user_id' => Auth::guard('api')->user()->user_id])->get(['role_id']);
            $permission_arr = [];
            $menu_item = [];
            foreach ($role_id as $r_id) {
                $permission = PermissionColumn::query()->where('role_id', $r_id['role_id'])->value('permission');

                if($permission )
                    array_push($permission_arr, json_decode($permission)->btns);
            }

            foreach ($data_type as $key => $item) {
                foreach ($permission_arr as $pr) {

                    if (in_array($item['btns_id'], $pr)) {
                        array_push($menu_item, $item);
                        break;
                    }
                }
            }



            return response(['code' => 1, 'msg' => 'SCCESS', 'result' => $menu_item]);
        }catch (Exception $e){
            return response(['code' => 0, 'msg' => 'ERROR', 'result' => $e]);
        }

    }

}