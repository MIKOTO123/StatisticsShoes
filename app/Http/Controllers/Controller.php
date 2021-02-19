<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @param $message
     * @param array $data
     * @param int $code 0代表成功,大于0表示有错误
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse($message, $data = [], $code = 0, $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ], $status);
    }


    public function ajaxSuccessResponse($message = '',  $data = [], $status = 200, $type = null)
    {
        return $this->ajaxResponse($message, $data, 0, $status, $type);
    }

    public function ajaxFaildResponse($message,  $data = [], $code = 1, $status = 200, $type = null)
    {
        return $this->ajaxResponse($message, $data, $code, $status, $type);
    }

    public function ajaxResponse($message,  $data = [], $code = 0, $status = 200, $type = null)
    {
        $accepts = \request()->getAcceptableContentTypes();
        $type = $type ?: (isset($accepts[0]) ? 'application/json' : $accepts[0]);
        switch (strtoupper($type)) {
            case Str::contains($type, ['/json', '+json']) :
                // 返回JSON数据格式到客户端 包含状态信息
                return $this->jsonResponse($message, $data, $code, $status);
            case Str::contains($type, ['/xml']) :
                // 返回xml格式数据
//                response()->header('Content-Type', 'text/xml; charset=utf-8');
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
            case 'EVAL' :
                // 返回可执行的js脚本
            default     :
                // 用于扩展其他返回格式数据

        }
    }


    /**
     * @param $message
     * @param array $data
     * @param array $errors
     * @param int $code
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonValidFaildResponse($message, array $data = [], $code = 0, array $errors = [], $status = 422)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'errors' => $errors,
        ], $status);
    }



    /*
     * 基础跳转
     */
    public function client()
    {
        return view('index');
    }

}
