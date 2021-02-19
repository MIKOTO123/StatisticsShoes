<?php


namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\UserOperlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class UsersLoginController extends Controller
{


    //

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function monologin()
    {
        return response()->json([
            'access_token' => '123',
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}

