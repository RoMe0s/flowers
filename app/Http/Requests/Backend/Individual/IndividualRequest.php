<?php

namespace App\Http\Requests\Backend\Individual;

use App\Http\Requests\FormRequest;

class IndividualRequest extends FormRequest
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
            'phone' => 'required',
            'email' => 'required|email',
            'image' => ['regex:'.$regex],
            'price' => ['required','regex:' . $pregex],
            'text'  => 'required'
        ];
    }
}
