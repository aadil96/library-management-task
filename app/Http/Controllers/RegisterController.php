<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Lib\ApiCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    public function register(RegisterRequest $request)
    {
        $request->merge([
            'gender' => strtolower($request->gender),
            'password' => Hash::make($request->password)
        ]);

        User::create($request->all());

        return respond(ApiCode::SUCCESS);
    }
}
