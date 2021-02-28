<?php


namespace App\Custom\Utils;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class PathUtils
{
    /**
     * 获取上传的xls路径
     */
    public static function getUploadImportPath()
    {
        //COns
        $date = Carbon::now()->toDateString();
        return "upload/import/{$date}";
    }

    /**
     * 获取上传的shop导入的xls路径
     */
    public static function getUploadImportShopPath()
    {
        //COns
        $date = Carbon::now()->toDateString();
        return "upload/import/shop/{$date}";
    }


    /**
     * 获取上传的shop导入的xls路径
     */
    public static function getUploadImportGoodPath()
    {
        //COns
        $date = Carbon::now()->toDateString();
        return "upload/import/good/{$date}";
    }

}
