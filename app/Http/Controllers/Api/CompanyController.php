<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;


use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CompanyController extends Controller
{
    public function index(Request $request)
    {

        $company = new Company();
        $company->pid = $request->post('pid');
        $company->name = $request->post('name');
        $company->describe = $request->post('describe', '');
        $company->code = $request->post('code', '');
        $company->nature = $request->post('nature', '');
        $company->address = $request->post('address', '');
        $company->is_flag = $request->post('is_flag', 1);
        $company->order = $request->post('order', 0);

        if ($company->save()) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }

    public function update(Request $request)
    {

        if (!$request->post('company_id')||!Company::find($request->post('company_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);
        $company = Company::find($request->post('company_id'));
        $company->pid = $request->post('pid');
        $company->name = $request->post('name');
        $company->describe = $request->post('describe', '');
        $company->code = $request->post('code', '');
        $company->nature = $request->post('nature', '');
        $company->address = $request->post('address', '');
        $company->is_flag = $request->post('is_flag', 1);
        $company->order = $request->post('order', 0);

        if ($company->update()) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }
    public function del(Request $request)
    {

        if (!$request->post('company_id')||!Company::find($request->post('company_id')))
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.NOT_ID'), 'result' => '']);

        if(Company::query()->where(['pid'=>$request->post('company_id')])->count())
            return response(['code' => trans('constants.NO_CODE'), 'msg' => trans('constants.DEL_SUBMENU'), 'result' => '']);


        if (Company::destroy($request->post('company_id'))) {
            return response(['code' => trans('constants.IS_CODE'), 'msg' => '', 'result' => '']);
        }
        return response(['code' => trans('constants.NO_CODE'), 'msg' => '', 'result' => '']);


    }
    public function list()
    {
            $data_type = Company::all();
            return response(['code' => 1, 'msg' => '', 'result' => $data_type]);
    }

}