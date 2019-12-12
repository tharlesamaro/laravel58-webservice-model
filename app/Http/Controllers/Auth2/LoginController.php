<?php

namespace App\Http\Controllers\Auth2;

use App\Http\Requests\Auth2\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    const STATUS_SUCCESS = 200;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_ERROR = 500;

    public function login(LoginRequest $_request)
    {
        try {
            if (Auth::attempt(['email' => $_request->email, 'password' => $_request->password])) {

                $_user = Auth::user();

                $_user->token = $_user->createToken($_user->email)->accessToken;

                return response()->json([
                    'status' => 'success',
                    'user' => $_user
                ], self::STATUS_SUCCESS);
            }

            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], self::STATUS_UNAUTHORIZED);

        } catch (\Throwable $_error) {
            Log::error($_error->getMessage());
        }

        return response()->json(['status' => 'error', 'message' => 'fatal error'], self::STATUS_ERROR);
    }
}
