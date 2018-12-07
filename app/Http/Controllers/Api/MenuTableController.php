<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;

use App\Models\MenuTable;
use Schema;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class MenuTableController extends Controller
{
    public function index(Request $request)
    {
        $menu_table = new MenuTable();
        try {
            $menu_table->menu_id = $request->post('menu_id');
            $menu_table->table_name = "table_" . $request->post('table_name') . "_" . time();
            $menu_table->show_name = $request->post('show_name');
            if ($menu_table->save()) {

//                Schema::table('table_test_1542334936', function ($table) {
//                    //$table->increments('id');
//                    $table->string('title');
//
//                });

                if (!Schema::hasTable($menu_table->table_name)) {
                    Schema::create($menu_table->table_name, function ($table) {
                        $table->increments('id');
                        $table->unsignedInteger('company_id');
                        $table->string('file_hooking');
                    });


                }

                return response(['code' => 1, 'msg' => '添加成功', 'result' => '']);
            }
        } catch (\Exception $e) {
            return response(['code' => 0, 'msg' => '添加失败', 'result' => $e]);
        }
    }

    public function update(Request $request)
    {
        $menu_table_id = $request->post('menu_table_id');
        $menu_table =  MenuTable::find($menu_table_id);
        $old_table=$menu_table['table_name'];

        try {
            $menu_table->menu_id = $request->post('menu_id');
            $menu_table->table_name = "table_" . $request->post('table_name') . "_" . time();
            $menu_table->show_name = $request->post('show_name');
            if ($menu_table->update()) {

                if (Schema::hasTable($old_table)) {
                    Schema::rename( $old_table,$menu_table->table_name);
                }

                return response(['code' => 1, 'msg' => '修改成功', 'result' => '']);
            }
        } catch (\Exception $e) {
            return response(['code' => 0, 'msg' => '添加失败', 'result' => $e]);
        }
    }

    public function del(Request $request)
    {
        $menu_table_id = $request->post('menu_table_id');

        try {

            if (Schema::hasTable(MenuTable::find($menu_table_id)['table_name'])) {
                Schema::drop(MenuTable::find($menu_table_id)['table_name']);
            }

            if (MenuTable::destroy($menu_table_id)) {

                return response(['code' => 1, 'msg' => '删除成功', 'result' => '']);
            } else {
                return response(['code' => 0, 'msg' => '删除失败', 'result' => '']);

            }
        } catch (Exception $e) {
            return response(['code' => 0, 'msg' => '删除失败', 'result' => '']);
        }
    }
    public function info(Request $request)
    {
        try {
            $menu_table =  MenuTable::where( 'menu_id',$request->post('menu_id'))->get(['menu_table_id','menu_id','table_name','show_name']);
            return response($menu_table);
        }catch (Exception $e){

        }

    }

}
