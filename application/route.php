<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
    //Banner
    Route::get('api/:version/banner','api/:version.Banner/getBanner');
    Route::get('api/:version/province','api/:version.Address/getProvince');
    Route::get('api/:version/school/[:id]','api/:version.Address/getSchoolByProID');


    Route::post('api/:version/token/user','api/:version.Token/getToken');
    Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

    Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
    Route::get('api/:version/address','api/:version.Address/getUserAddress');

    Route::post('api/:version/order','api/:version.Order/placeOrder');
    Route::get('api/:version/order/[:page]','api/:version.Order/getUserOrder');
    Route::get('api/:version/order/all/[:page]','api/:version.Order/getAllOrder');
    Route::get('api/:version/order/detail/[:id]','api/:version.Order/getOrderDetail');

    Route::post('api/:version/order/pack','api/:version.Order/packOrder');
    Route::delete('api/:version/order/delete','api/:version.Order/deleteOrder');

    Route::get('api/:version/order/packedOrder/[:page]','api/:version.Order/getPackedOrder');
    Route::post('api/:version/order/confirm','api/:version.Order/confirmOrder');
    Route::post('api/:version/order/cancel','api/:version.Order/cancelOrder');

    Route::post('api/:version/scope/apply','api/:version.Scope/applyScope');
    Route::get('api/:version/scope/getStatus','api/:version.Scope/getStatus');
    Route::post('api/:version/feedback','api/:version.Scope/feedback');
    Route::get('api/:version/agreement','api/:version.Scope/agreement');
    Route::post('api/:version/getShortMsgCode','api/:version.ShortMessage/getShortMsgCode');
    Route::post('api/:version/uploadImg','api/:version.Upload/uploadImg');



    Route::get('manual0289','manual/Index/index');




    Route::get('api/:version/test/[:value]','api/:version.TimeTest/test');
    Route::post('api/:version/test/[:value]','api/:version.TimeTest/test');


