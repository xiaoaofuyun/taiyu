<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;

//use Maatwebsite\Excel\Facades\Excel;
use App\Models\MenuTable;
use App\Models\PackZip;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\PermissionColumn;
use App\Models\UserRole;


class TableDataController extends Controller
{
    private $excel;
    private $menu_table_id = 0;
    private $is_imp = 0;
    private $company_id = 0;

    public function __construct(Excel $excel, Request $request)
    {
        $this->company_id = Auth::guard('api')->user()->company_id;
        $this->excel = $excel;

        $role_id = UserRole::where(['user_id' => Auth::guard('api')->user()->user_id])->get(['role_id'])->toArray();
        $permission_arr = [];

        foreach ($role_id as $r_id) {
            $permission = PermissionColumn::query()->where('role_id', $r_id['role_id'])->value('permission');
            if ($permission)
                array_push($permission_arr, json_decode($permission)->table);
        }

        if ($menu_table_id = $request->all()['menu_table_id']) {
            foreach ($permission_arr as $pr) {

                if (!in_array($menu_table_id, $pr)) {

                    echo json_encode(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.PERMISSION_ERROR'), 'result' => '']);
                    exit;

                }

            }
        }

    }

    public function index(Request $request)
    {
        $arr = $request->all();

        if (count($arr) > 0) {
            $menu_table = DB::table('menu_table')->where(['menu_table_id' => $arr['menu_table_id']])->first();
            //
            foreach ($arr['data'] as &$v) {
                $v['company_id'] = $this->company_id;
            }

            if ($ids = DB::table($menu_table->table_name)->insert($arr['data'])) {


                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
            return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);

        }
    }

    public function list(Request $request)
    {
        $arr = $request->all();
        if (count($arr) > 0) {
            $page = $arr['page'] ?? 1;
            $pageSize = $arr['pagesize'] ?? 10;
            $offset = ($page - 1) * $pageSize;

            $menu_table = DB::table('menu_table')->where(['menu_table_id' => $arr['menu_table_id']])->first();


            $td_list = Db::table($menu_table->table_name)->where($arr['where'])->where('company_id', $this->company_id)->orderBy($arr['order'][0], $arr['order'][1])->offset($offset)->limit($pageSize)->get();
            return response($td_list);
        }
    }

    public function update(Request $request)
    {
        $arr = $request->all();

        if (count($arr) > 0) {
            $menu_table = DB::table('menu_table')->where(['menu_table_id' => $arr['menu_table_id']])->first();


            if (DB::table($menu_table->table_name)->where('id', $arr['id'])->where('company_id', $this->company_id)->update($arr['data'])) {

                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
            return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
        }
    }

    public function del(Request $request)
    {
        if (!$request->post('menu_table_id') || !$request->post('id'))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $menu_table = DB::table('menu_table')->where(['menu_table_id' => $request->post('menu_table_id')])->first();
        if (DB::table($menu_table->table_name)->delete($request->post('id'))) {

            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
    }

    public function imexcel(Request $request)
    {

        $file_arr = $this->upload($request, 'tmp', date('Y-m-d'), array('xlsx', 'xls'), true);
        if ($file_arr) {
            Db::table('tmp_file')->insert(['name' => $file_arr['name'], 'path' => 'storage/app/tmp/' . $file_arr['path'], 'ext' => $file_arr['ext']]);

            $filePath = 'storage/app/tmp/' . iconv('UTF-8', 'GBK', $file_arr['path']);
            $this->menu_table_id = $request->post('menu_table_id');

            Excel::load($filePath, function ($reader) {
                $data = $reader->toArray();
                $menu_table = DB::table('menu_table')->where(['menu_table_id' => $this->menu_table_id])->first();

                foreach ($data as &$v) {
                    $v['company_id'] = $this->company_id;
                }

                if (DB::table($menu_table->table_name)->insert($data)) {
                    $this->is_imp = 1;


                }
            });

            if ($this->is_imp) {
                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
            return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
            //  Excel::download($cellData,realpath(base_path('/storage/app/tmp/'.date('Y-m-d'))).'\test.xlsx');
        }
    }

    public function exexcel(Request $request)
    {

        $arr = $request->all();
        if (count($arr) > 0) {


            $menu_table = DB::table('menu_table')->where(['menu_table_id' => $arr['menu_table_id']])->first();

            $cellData = Db::table($menu_table->table_name)->where($arr['where'])->Where([])->get()->toArray();
            $cellData = json_decode(json_encode($cellData), true);
            $name = iconv('UTF-8', 'GBK', $menu_table->show_name);

            Excel::create($name, function ($excel) use ($cellData) {

                $excel->sheet('score', function ($sheet) use ($cellData) {

                    $sheet->rows($cellData);

                });

            })->store('xls')->export('xlsx');
        }
    }

    public function upfile(Request $request)
    {

        $file_arr = $this->upload($request, 'tdata', $request->post('menu_table_id') . '/' . $this->company_id, array('xlsx', 'xls'), false);
        if ($file_arr) {
            if (Db::table('files')->insert(['name' => $file_arr['name'], 'path' => 'storage/app/tdata/' . $file_arr['path'], 'ext' => $file_arr['ext'], 'menu_table_id' => $request->post('menu_table_id'), 'company_id' => $this->company_id])) {
                return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
            }
            return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
        }
    }

    public function downs($filepath = "", $filename = "")
    {
        $headers = ['Content-Type' => 'application/zip', 'charset=utf-8'];

        return response()->download(realpath(base_path($filepath)), $filename, $headers);
    }

    public function upload(Request $request, $upload_paty = 'public', $subpath = '', $ext_arr = '', $nfname = true)
    {
        header("Content-Type:text/html;charset=UTF-8");
        $fileCharater = $request->file('file');

        if ($fileCharater->isValid()) {

            $ext = $fileCharater->getClientOriginalExtension();

            if (is_array($ext_arr)) {

                if (empty(in_array($ext, $ext_arr))) {
                    echo json_encode(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.EXT_ERROR') . implode(",", $ext_arr), 'result' => '']);
                    exit;
                }

            }

            $path = $fileCharater->getRealPath();
            $filename = $subpath . '/' . uniqid($upload_paty . '_') . '.' . $ext;
            $filename_old = $fileCharater->getClientOriginalName();
            $filename = $nfname ? $filename : $subpath . '/' . $filename_old;

            //print_r();exit;
            $s = Storage::disk($upload_paty)->put($filename, file_get_contents($path));
            if ($s) {
                return [
                    'name' => substr($filename_old, 0, strrpos($filename_old, '.')),
                    // 'n_name'=>$fileCharater->getClientOriginalName(),
                    'path' => $subpath,
                    'ext' => $ext
                ];
            }

        }
        return [];
    }

    public function file_hooking(Request $request)
    {
        $arr = $request->all();

        if (count($arr) > 0) {
            try {
                $table_name = MenuTable::query()->where('menu_table_id', $arr['menu_table_id'])->value('table_name');
                $data = Db::table($table_name)->where('company_id', $this->company_id)->get(['id'])->toArray();
                foreach ($data as $v) {
                    $values = "";
                    foreach ($arr['field'] as $field) {
                        $values .= Db::table($table_name)->where('id', $v->id)->value($field) . '-';
                    }


                    Db::table($table_name)->where('id', $v->id)->update(['file_hooking' => substr($values, 0, strlen($values) - 1)]);

                }
            } catch (Exception $e) {

                return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);
            }
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
    }

    public function pack_zip(Request $request)
    {

        if (!$request->post('menu_table_id'))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $files = Array();
        $table = MenuTable::query()->where('menu_table_id', $request->post('menu_table_id'))->first();

        // $file_names = Db::table($table->table_name)->where('company_id', $this->company_id)->get(['file_hooking'])->toArray();

        //  foreach ($file_names as $fns) {
        //  $path = Db::table('files')->where('company_id', $this->company_id)->where('name', $fns->file_hooking)->value('path');
        // if ($path != null) {
        $check = glob(storage_path('app/tdata/' . $request->post('menu_table_id') . '/' . $this->company_id));

        $files = array_merge($files, $check);
        // }
        // }
        $file_name = date('YmdHis');
        Zipper::make(storage_path() . '/tdata/' . $request->post('menu_table_id') . '/' . $this->company_id . '/' . $file_name . '.zip')->add($files)->close();
        PackZip::query()->insert(['file_path' => 'storage/tdata/' . $request->post('menu_table_id') . '/' . $this->company_id, 'company_id' => $this->company_id, 'menu_table_id' => $request->post('menu_table_id'), 'name' => $file_name, 'ext' => '.zip']);
        return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
    }

    public function pzip_list(Request $request)
    {
        if (!$request->post('menu_table_id'))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $pack_zip = PackZip::query()->where('menu_table_id', $request->post('menu_table_id'))->where('company_id', $this->company_id)->get(['id', 'name']);
        return response($pack_zip->toArray());
    }

    public function pzip_down(Request $request)
    {
        if (!$request->post('id'))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $pack_zip = PackZip::query()->where('id', $request->post('id'))->where('company_id', $this->company_id)->first(['name', 'file_path', 'ext']);

        return response()->download(realpath(base_path($pack_zip->file_path . '/' . $pack_zip->name . $pack_zip->ext)), $pack_zip->name . '.' . $pack_zip->ext);

    }
}