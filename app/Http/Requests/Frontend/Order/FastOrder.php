<?php

namespace App\Http\Requests\Frontend\Order;

use App\Http\Requests\FormRequest;
use Sentry;

class FastOrder extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'phone'                 => 'required|string|regex:/^[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'agreement'             => 'required'
        ];

        $user = Sentry::getUser();

        if(!isset($user)) {

            $rules['password'] = 'required';

        }

        return $rules;
    }
}
