<?php

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\FormRequest;

class PasswordChange extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];
    }
}
