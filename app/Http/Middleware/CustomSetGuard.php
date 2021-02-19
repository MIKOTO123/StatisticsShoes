<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;

class CustomSetGuard
{


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        //设置auth的guard,后面会从容器中取出就不用在设置guard
        auth($guard);
        //动态修改guard让jwtauth自动注入
        Config::set("auth.defaults.guard", $guard);

        // 在响应头中返回新的 token
        return $next($request);
    }
}
