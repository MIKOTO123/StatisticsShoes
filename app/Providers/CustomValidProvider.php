<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class CustomValidProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        //    要被验证的属性名称 $attribute
//    属性的值 $value
//    传入验证规则的参数数组 $parameters
//    Validator 实例 ValidatesAttributes????
//
        Validator::extend('mobile', function ($attribute, $value, $parameters,  $validator) {
            return $validator->validateRegex($attribute, $value, ['/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/']);
        });
    }
}
