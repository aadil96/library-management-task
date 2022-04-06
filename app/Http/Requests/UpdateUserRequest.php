<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'alpha', 'max:255'],
            'last_name' => ['required', 'alpha', 'max:255'],
            // 'password' => ['required', 'string', 'max:255', 'confirmed'],
            'age' => ['required', 'integer', 'between:1,100'],
            'gender' => ['required', 'in:Male,Female,Other,male,female,other'],
            'mobile' => ['required', 'numeric', 'digits:10', 'unique:users,mobile,'.Auth::id()],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email,'.Auth::id()],
            'city' => ['required', 'string', 'max:255'],
        ];
    }
}
