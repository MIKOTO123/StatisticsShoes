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
    });

    Route::prefix('good')->group(function () {
        Route::any('checkExist', 'GoodController@checkExist');
    });

    Route::resources([
        'shop' => "ShopController",
        'good' => "GoodController",
    ]);
});

