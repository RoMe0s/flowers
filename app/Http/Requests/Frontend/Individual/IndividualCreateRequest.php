<?php

namespace App\Http\Requests\Frontend\Individual;

use App\Http\Requests\FormRequest;

class IndividualCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';

        $pregex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        return [
            'phone' => 'required|regex:' . config('user.phone_regex'),
            'email' => 'required|email',
            'image' => ['required'/*, 'regex:'.$regex*/],
            'price' => ['required','regex:' . $pregex]
        ];
    }
}
