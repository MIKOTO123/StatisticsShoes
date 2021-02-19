<?php


namespace App\Custom\Utils;


class CacheHelper
{

    const FORM_ADMIN_CACHE_KEY = 'FORM_{admin_id}_{request_md5}';
    const FORM_CLIENT_CAHCE_KEY = 'FORM_{user_id}_{request_md5}';


    public static function getFormAdminCachedKey($request_arr, $admin_id = null)
    {
        $admin_id = $admin_id ?? auth()->id();
        $request_md5 = md5(implode(',', $request_arr));
        $replace_arr = [
            '{admin_id}' => $admin_id,
            '{request_md5}' => $request_md5,
        ];
        return str_replace(array_keys($replace_arr), array_values($replace_arr), self::FORM_ADMIN_CACHE_KEY);
    }

    public static function getFormClientCachedKey($request_arr, $user_id= null)
    {
        $user_id= $user_id?? auth()->id();
        $request_md5 = md5(implode(',', $request_arr));
        $replace_arr = [
            '{user_id}' => $user_id,
            '{request_md5}' => $request_md5,
        ];
        return str_replace(array_keys($replace_arr), array_values($replace_arr), self::FORM_ADMIN_CACHE_KEY);
    }


}
