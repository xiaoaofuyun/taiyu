<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;

use App\Models\Btns;
use App\Models\MenuTable;
use Route;
use Illuminate\Http\Request;
use App\Models\TreeMenu;
use App\Models\PermissionColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class PermissionColumnController extends Controller
{
    public function list()
    {

        $permission_list=[];
        $menu=TreeMenu::query()->get(  ['tree_menu_id','name'])->toArray();
        $table=MenuTable::query()->get(  ['menu_table_id','show_name'])->toArray();
        $btns=Btns::query()->get(  ['btns_id','name'])->toArray();

        $routes = Route::getRoutes();
        $rotes_arr="";
        $i=0;
        foreach ($routes as $key=>$route) {


            if(array_key_exists($route->uri,trans('route'))){

                $rotes_arr[$i]['uri']=$route->uri;
                $rotes_arr[$i]['name']=trans('route.'.$route->uri);
                $i++;
            }
        }
        $permission_list['sys']=$rotes_arr;
        $permission_list['menu']=$menu;
        $permission_list['table']=$table;
        $permission_list['btns']=$btns;

        return response($permission_list);
    }
    public function index(Request $request){
        $permission=$request->all();
        if (count($permission)>0){

            $permission_column=new PermissionColumn();
            $permission_column->role_id=$permission['role_id'];
            array_forget($permission,'role_id');
            $permission_column->permission=json_encode($permission);
            if($permission_column->save()){
                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }
    public function update(Request $request){
        $permission=$request->all();

        if (count($permission)==6){
            $permission_column= PermissionColumn::find($permission['permission_column_id']);
            $permission_column->role_id=$permission['role_id'];
            array_forget($permission,['role_id','permission_column_id']);
            $permission_column->permission=json_encode($permission);
            if($permission_column->update()){
                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }
    public function del(Request $request)
    {
        if (!$request->post('permission_column_id') || !PermissionColumn::find($request->post('permission_column_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);

        if (PermissionColumn::destroy($request->post('permission_column_id'))) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }
    public function rlist(Request $request)
    {
        if (!$request->post('role_id') )
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
         $rlist= PermissionColumn::query()->where(['role_id'=>$request->post('role_id')])->get()->toArray();
         foreach ($rlist as &$v){
             $v['permission']=json_decode($v['permission']);
         }
         return response($rlist);
    }

}