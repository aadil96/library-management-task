<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Lib\ApiCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    public function login(LoginRequest $request) : JsonResponse
    {
        try {
            if (! $token = JWTAuth::attempt($request->all())) {
                return respondWithError(
                    ApiCode::VALIDATION_ERROR,
                    ['email' => 'The credentials do not match our records.']
                );
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return respond(ApiCode::OK, [
            'token' => $token
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return respondWithMessage('Logged out');
    }
}
