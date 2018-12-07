<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/9
 * Time: 19:08
 */

namespace App\Http\Controllers\Api;

use App\Models\PermissionColumn;
use App\Models\TreeMenu;
use App\Models\UserRole;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TreeMenuController extends BaseController
{


    public function index(Request $request)
    {

        if ($request->isMethod('post')) {
            $name = $request->post('name', '');
            if (empty($name)) {
                return response(['code' => 0, 'msg' => '添加失败', 'result' => '']);
            }
            $order = $request->post('order', 0);
            $pid = $request->post('pid', 0);
            $tree_menu = new TreeMenu();
            $tree_menu->name = $name;
            $tree_menu->order = $order;
            $tree_menu->pid = $pid;
            if ($tree_menu->save()) {
                return response(['code' => 1, 'msg' => '添加成功', 'result' => '']);
            } else {
                return response(['code' => 0, 'msg' => '添加失败', 'result' => '']);

            }
        }
    }

    public function list()
    {

        $tree_menu = new TreeMenu();
        $items = $tree_menu->getCategoryInfo();


        $role_id = UserRole::where(['user_id' => Auth::guard('api')->user()->user_id])->get(['role_id'])->toArray();
        $permission_arr = [];
        $menu_item = [];
        foreach ($role_id as $r_id) {
            $permission = PermissionColumn::query()->where('role_id', $r_id['role_id'])->value('permission');
            if($permission )
            array_push($permission_arr, json_decode($permission)->menu);
        }

        foreach ($items as $key => $item) {
            foreach ($permission_arr as $pr) {
                if (in_array($item['tree_menu_id'], $pr)) {
                    array_push($menu_item, $item);
                    break;
                }
            }
        }
        return response($menu_item);


    }

    public function update(Request $request)
    {

        if ($request->isMethod('post') && $request->post('tree_menu_id')) {

            $tree_menu_id = $request->post('tree_menu_id', '');
            $name = $request->post('name', '');
            $order = $request->post('order', 0);
            $pid = $request->post('pid', 0);
            $tree_menu = TreeMenu::find($tree_menu_id);

            if ($tree_menu) {
                $tree_menu->name = $name;
                $tree_menu->order = $order;
                $tree_menu->pid = $pid;
                $tree_menu->save();
                if ($tree_menu->save()) {
                    return response(['code' => 1, 'msg' => '修改成功', 'result' => '']);
                } else {
                    return response(['code' => 0, 'msg' => '修改失败', 'result' => '']);

                }
            }

        } else {
            return response(['code' => 1, 'msg' => '修改失败', 'result' => '']);
        }
    }

    public function del(Request $request)
    {


        if ($request->isMethod('post') && $request->post('tree_menu_id')) {

            $tree_menu_id = $request->post('tree_menu_id');


            if (TreeMenu::query()->where(['pid' => $tree_menu_id])->count()) {
                return response(['code' => -1, 'msg' => trans('constants.DEL_SUBMENU'), 'result' => '']);
            }

            if (TreeMenu::destroy($tree_menu_id)) {
                return response(['code' => 1, 'msg' => '删除成功', 'result' => '']);
            } else {
                return response(['code' => 0, 'msg' => '删除失败', 'result' => '']);

            }
        }
    }
}