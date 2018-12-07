<?php

use Illuminate\Http\Request;



Route::group(['middleware' => ['auth.sign']], function () {

    Route::post('login', ['uses' => 'Api\UsersController@login']);
});

Route::group(['middleware' => ['auth.api','auth.permission','auth.operation']], function () {

    Route::post('user/create', ['uses' => 'Api\UsersController@create']);
    Route::post('user/update', ['uses' => 'Api\UsersController@update']);
    Route::post('user/list', ['uses' => 'Api\UsersController@list']);
    Route::post('user/del', ['uses' => 'Api\UsersController@del']);
    Route::post('user/repwd', ['uses' => 'Api\UsersController@repwd']);
    Route::post('user/dlist', ['uses' => 'Api\UsersController@dlist']);


    Route::post('menu/list', ['uses' => 'Api\TreeMenuController@list']);
    Route::post('menu/index', ['uses' => 'Api\TreeMenuController@index']);
    Route::post('menu/update', ['uses' => 'Api\TreeMenuController@update']);
    Route::post('menu/del', ['uses' => 'Api\TreeMenuController@del']);

    Route::post('mtable/index', ['uses' => 'Api\MenuTableController@index']);
    Route::post('mtable/del', ['uses' => 'Api\MenuTableController@del']);
    Route::post('mtable/update', ['uses' => 'Api\MenuTableController@update']);
    Route::post('mtable/info', ['uses' => 'Api\MenuTableController@info']);

    Route::put('tfield/index', ['uses' => 'Api\TableFieldController@index']);
    Route::post('tfield/list', ['uses' => 'Api\TableFieldController@list']);
    Route::post('tfield/del', ['uses' => 'Api\TableFieldController@del']);

    Route::post('dtype/list', ['uses' => 'Api\DataTypeController@list']);
    Route::post('btns/list', ['uses' => 'Api\BtnsController@list']);

    Route::post('company/index', ['uses' => 'Api\CompanyController@index']);
    Route::post('company/update', ['uses' => 'Api\CompanyController@update']);
    Route::post('company/del', ['uses' => 'Api\CompanyController@del']);
    Route::post('company/list', ['uses' => 'Api\CompanyController@list']);

    Route::post('department/index', ['uses' => 'Api\DepartmentController@index']);
    Route::post('department/del', ['uses' => 'Api\DepartmentController@del']);
    Route::post('department/update', ['uses' => 'Api\DepartmentController@update']);
    Route::post('department/list', ['uses' => 'Api\DepartmentController@list']);

    Route::post('role/index', ['uses' => 'Api\RoleController@index']);
    Route::post('role/update', ['uses' => 'Api\RoleController@update']);
    Route::post('role/del', ['uses' => 'Api\RoleController@del']);
    Route::post('role/ulist', ['uses' => 'Api\RoleController@ulist']);
    Route::post('role/list', ['uses' => 'Api\RoleController@list']);
    Route::put('role/createur', ['uses' => 'Api\RoleController@createur']);
    Route::post('role/listur', ['uses' => 'Api\RoleController@listur']);

    Route::post('permission/list', ['uses' => 'Api\PermissionColumnController@list']);
    Route::put('permission/index', ['uses' => 'Api\PermissionColumnController@index']);
    Route::put('permission/update', ['uses' => 'Api\PermissionColumnController@update']);
    Route::post('permission/del', ['uses' => 'Api\PermissionColumnController@del']);
    Route::post('permission/rlist', ['uses' => 'Api\PermissionColumnController@rlist']);

    Route::put('tabledate/index', ['uses' => 'Api\TableDataController@index']);
    Route::put('tabledate/update', ['uses' => 'Api\TableDataController@update']);
    Route::put('tabledate/list', ['uses' => 'Api\TableDataController@list']);
    Route::post('tabledate/del', ['uses' => 'Api\TableDataController@del']);

    Route::post('tabledate/upload', ['uses' => 'Api\TableDataController@upload']);
    Route::post('tabledate/imexcel', ['uses' => 'Api\TableDataController@imexcel']);
    Route::put('tabledate/exexcel', ['uses' => 'Api\TableDataController@exexcel']);

    Route::post('tabledate/upfile', ['uses' => 'Api\TableDataController@upfile']);
    Route::any('tabledate/downs', ['uses' => 'Api\TableDataController@downs']);
    Route::put('tabledate/file_hooking', ['uses' => 'Api\TableDataController@file_hooking']);
    Route::post('tabledate/pack_zip', ['uses' => 'Api\TableDataController@pack_zip']);
    Route::post('tabledate/pzip_list', ['uses' => 'Api\TableDataController@pzip_list']);
    Route::post('tabledate/pzip_down', ['uses' => 'Api\TableDataController@pzip_down']);



});

Route::any('test', function (Request $request) {
$j=  ['a'=>[2,3,4,5,6,8]];
 print_r(json_encode($j));
});