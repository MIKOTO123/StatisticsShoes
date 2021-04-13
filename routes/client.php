<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::namespace('Client')->prefix('contacts')->group(function () {
    Route::any('downLoadStandardXls', 'ContactsController@downLoadStandardXls');
});


Route::namespace('Client')->group(function ($router) {
    Route::post('login', 'UsersLoginController@monologin');


    Route::prefix('shop')->group(function () {
        Route::any('checkExist', 'ShopController@checkExist');
        Route::any('getOptionsShop', 'ShopController@getOptionsShop');
        Route::any('shopImport', 'ShopController@shopImport');
    });


    Route::prefix('area')->group(function () {
        Route::any('checkExist', 'AreaController@checkExist');
    });


    Route::prefix('good')->group(function () {
        Route::any('checkExist', 'GoodController@checkExist');
        Route::any('goodImport/{shop_id}', 'GoodController@goodImport');
    });

    Route::prefix('statistics')->group(function () {
        Route::any('checkExist', 'StatisticsController@checkExist');
    });


    Route::prefix('statisticsSingle')->group(function () {
        Route::any('decrement', 'StatisticsSingleController@decrement');
        Route::any('uploadImport/{s_id}', 'StatisticsSingleController@uploadImport');
    });


    Route::resources([
        'shop' => "ShopController",
        'area' => "AreaController",
        'good' => "GoodController",
        'statistics' => "StatisticsController",
        'statisticsSingle' => "StatisticsSingleController",
        'importError' => "ImportErrorController",
    ]);
});

