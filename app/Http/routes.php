<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth'], function() {
    
    Route::get('/', 'BugController@index'); // 首页-只显示需要自己解决的bug
    Route::get('/create', 'BugController@create'); // 跳转到添加页面
    Route::post('/store', 'BugController@store'); // 添加一条bug信息 
    Route::get('/show/{id}', 'BugController@show')->where('id', '[0-9]+'); // 查看 一条bug
    Route::get('/edit/{id}', 'BugController@edit')->where('id', '[0-9]+'); // 跳转到 修改界面
    Route::get('/fix/{id}', 'BugController@fix')->where('id', '[0-9]+'); // 跳转到 修复界面
    Route::post('/update/{id}', 'BugController@update')->where('id', '[0-9]+'); // 修改一条bug信息 
    Route::get('/negotiate/{id}', 'BugController@negotiate')->where('id', '[0-9]+'); // standby一条bug信息 
    Route::get('/search/{id}/{status}', 'BugController@search')
            ->where([
                'id' => '[0-9]+',
                'status' => '[0-9]+'
            ]); // 根据条件查询bug，无条件则返回全部bug
    Route::get('/fuzzysearch/{keywords}', 'BugController@fuzzySearch');
    Route::get('/all', 'BugController@all');
    
//    Route::get('/success', 'SuccessController@index');
    
//    Route::get('/richeditor', function(){
//        return view('wysiwyg');
//    });
    
//    Route::get('/bugimg/{id}', 'BugController@viewImg');
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
