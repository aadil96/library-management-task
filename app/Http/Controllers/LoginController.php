<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Lib\ApiCode;
use App\Lib\CustomResponseBuilder;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function login(LoginRequest $request) : JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();

        if (Auth::attempt($request->all())) {
            return respond(ApiCode::OK, [
                'token' => $user->createToken('secret')->accessToken
            ]);
        }

        return respondWithError(ApiCode::VALIDATION_ERROR, ['email' => 'The credentials do not match our records.']);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->token()->revoke();
        return respondWithMessage('Logged out');
    }
}
