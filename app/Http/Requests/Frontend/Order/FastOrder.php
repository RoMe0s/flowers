<?php

namespace App\Http\Requests\Frontend\Order;

use App\Http\Requests\FormRequest;

class FastOrder extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'                 => 'required|email',
            'phone'                 => 'required|string|regex:/^[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'agreement'             => 'required'
        ];
    }
}
