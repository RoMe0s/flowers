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
            'phone'                 => 'required|string|regex:' . config('user.phone_regex'),
            'agreement'             => 'required'
        ];

        $user = Sentry::getUser();

        if(!isset($user)) {

            $rules['password'] = 'required';

        }

        return $rules;
    }
}
