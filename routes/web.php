<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',function(){
    echo date('Y-m-d H:i:s');
});

/**微信开发 */
Route::get('wechat/index','WechatController@index');

Route::post('wechat/index','WechatController@index');
Route::get('wechat/createMenu','WechatController@createMenu');//创建自定义菜单
Route::get('wechat/sendmsg','WechatController@sendAllByOpenId');//微信群发

// 后台首页
Route::prefix('admin/')->group(function(){
    Route::get('index','Admin\IndexController@index');
    Route::get('index_v1','Admin\IndexController@index_v1');
    Route::get('getWeather','Admin\IndexController@getWeather');//调用天气接口
});
// 后台登录
Route::get('admin/login','Admin\LoginController@login');//展示登陆视图
Route::post('admin/doLogin','Admin\LoginController@doLogin');//执行登陆

// 素材管理
Route::get('media/add','Admin\MediaController@add');//展示素材添加视图
Route::post('media/add_do','Admin\MediaController@add_do');//素材添加
Route::get('media/show','Admin\MediaController@show');//展示素材列表



// 新闻管理
Route::get('new/create','NewController@create'); //展示新闻添加视图
Route::post('new/store','NewController@store'); //执行添加
Route::get('new/index','NewController@index'); //列表展示
Route::get('new/destroy/{id}','NewController@destroy'); //删除
Route::get('new/edit/{id}','NewController@edit'); //展示修改视图
Route::post('new/update/{id}','NewController@update'); //执行修改

Route::any('new/weixin','NewController@weixin');

// 渠道管理
Route::get('channel/add','Admin\ChannelController@add');
Route::post('channel/add_do','Admin\ChannelController@add_do');
Route::get('channel/index','Admin\ChannelController@index');

// 统计图表
Route::get('chart/index','Admin\ChartController@index');

//自动上线 
Route::any('/gitpull','Git\IndexController@index');

//微信网页授权
Route::get('/wechat/test','WechatController@test');   //测试
Route::get('/wechat/auth','WechatController@auth');   //接收 code

//发送模板消息
Route::post('admin/test','Admin\LoginController@test');


