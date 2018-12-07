<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;


use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class DepartmentController extends Controller
{
    public function index(Request $request)
    {

        $company = new Department();
        $company->company_id = $request->post('company_id');
        $company->name = $request->post('name');
        $company->describe = $request->post('describe', '');
        $company->code = $request->post('code', '');
        $company->is_flag = $request->post('is_flag', 1);
        $company->order = $request->post('order', 0);

        if ($company->save()) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }

    public function update(Request $request)
    {

        if (!$request->post('department_id') || !Department::find($request->post('department_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $company = Department::find($request->post('department_id'));
        $company->company_id = $request->post('company_id');
        $company->name = $request->post('name');
        $company->describe = $request->post('describe', ' ');
        $company->code = $request->post('code', '');
        $company->is_flag = $request->post('is_flag', 1);
        $company->order = $request->post('order', 0);

        if ($company->update()) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }

    public function del(Request $request)
    {
        if (!$request->post('department_id') || !Department::find($request->post('department_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);

        if (Department::destroy($request->post('department_id'))) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }

    public function list(Request $request)
    {
        if (!$request->post('company_id') )
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $data_type = Department::query()->where(['company_id' => $request->post('company_id')])->get();
        return response(['code' => 1, 'msg' => '', 'result' => $data_type]);
    }

}