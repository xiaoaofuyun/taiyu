<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:34
 */

namespace App\Http\Controllers\Api;


use App\Models\DataType;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;



class DataTypeController extends Controller
{
    public function list(Request $request)
    {
        try{
           $data_type= DataType::query()->where(['type'=>$request->post('type')])->get();
            return response(['code' => 1, 'msg' => 'SCCESS', 'result' => $data_type]);
        }catch (Exception $e){
            return response(['code' => 0, 'msg' => 'ERROR', 'result' => $e]);
        }

    }

}