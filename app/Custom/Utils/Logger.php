<?php


namespace App\Custom\Utils;


use Illuminate\Support\Facades\Log;

class Logger
{
    /**
     * 短信日志
     * @param $message
     */
    public static function smsLog($message)
    {
        Log::channel('smslog')->info($message);
    }


    /**
     * 短信日志
     * @param $message
     */
    public static function debugLog($message)
    {
        Log::channel('debuglog')->info($message);
    }


    /**
     * 短信日志
     * @param $message
     */
    public static function importLog($message)
    {
        Log::channel('importlog')->info($message);
    }


    /**
     * 支付日志
     * @param $message
     * @param string $level info|debug|error
     */
    public static function payLog($message, $level = 'info')
    {
        Log::channel('paylog')->{$level}($message);
    }


    /**
     * 短信日志
     * @param $message
     */
    public static function queueLog($message, $level = 'info')
    {
        Log::channel('queueLog')->{$level}($message);
    }


    /**
     * 短信日志
     * @param $message
     */
    public static function commandLog($message, $level = 'info')
    {
        Log::channel('commandLog')->{$level}($message);
    }


}
