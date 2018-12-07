<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;

use App\Models\TableField;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Schema;

class TableFieldController extends Controller
{
    private $c_v;
    private $table;
    private $field;
    public function index(Request $request)
    {


        $arr = $request->all();
        if (count($arr) > 0) {
            foreach ($arr as $v) {
                $this->c_v = $v;
                $menu_table = DB::table('menu_table')->where(['menu_table_id' => $v['menu_table_id']])->first();
                $this->table = $menu_table->table_name;


                if(Schema::hasTable($this->table)) {
                    Schema::table($menu_table->table_name, function ($table) {

                        if (!Schema::hasColumn($this->table, $this->c_v['field_name'])) {

                            if ($this->c_v['data_type'] == "t_int") {
                                $table->unsignedInteger($this->c_v['field_name']);
                            } elseif ($this->c_v['data_type'] == "t_string") {
                                $table->string($this->c_v['field_name']);
                            } elseif ($this->c_v['data_type'] == "t_date") {
                                $table->date($this->c_v['field_name']);
                            } elseif ($this->c_v['data_type'] == "t_text") {
                                $table->text($this->c_v['field_name']);
                            } elseif ($this->c_v['data_type'] == "t_float") {
                                $table->float($this->c_v['field_name']);
                            }
                            $table_field = new TableField();
                            $table_field->menu_table_id = $this->c_v['menu_table_id'];
                            $table_field->field_name = $this->c_v['field_name'];
                            $table_field->field_show = $this->c_v['field_show'];
                            $table_field->data_type = $this->c_v['data_type'];
                            $table_field->parameter_type = $this->c_v['parameter_type'];
                            $table_field->is_show = $this->c_v['is_show'];
                            $table_field->view_with = $this->c_v['view_with'];
                            $table_field->order = $this->c_v['order'];
                            $table_field->is_required = $this->c_v['is_required'];
                            $table_field->is_indexes = $this->c_v['is_indexes'];
                            $table_field->save();
                        }

                    });
                } else {
                    return response(['code' => 0, 'msg' => '数据库不存在', 'result' => ""]);
                }


            }
            return response(['code' => 1, 'msg' => '添加成功', 'result' => ""]);
        }
        return response(['code' => 0, 'msg' => '添加失败', 'result' => ""]);
    }

    public function list(Request $request)
    {
        if($request->post('menu_table_id'))
        return response(TableField::query()->where('menu_table_id',$request->post('menu_table_id'))->get()->toArray());
    }

    public function del(Request $request)
    {


        try {
            $table_field=TableField::find($request->post('table_field_id'));

             $this->table = DB::table('menu_table')->where(['menu_table_id' => $table_field->menu_table_id])->first()->table_name;

            $this->field=$table_field->field_name;
            if (Schema::hasColumn($this->table, $this->field)) {
                Schema::table( $this->table, function($table) {
                    $table->dropColumn( $this->field);
                });
            }

            if (TableField::destroy($table_field->table_field_id)) {

                return response(['code' => 1, 'msg' => '', 'result' => '']);
            } else {
                return response(['code' => 0, 'msg' => '', 'result' => '']);

            }
        } catch (Exception $e) {
            return response(['code' => 0, 'msg' => '', 'result' => '']);
        }
    }
}