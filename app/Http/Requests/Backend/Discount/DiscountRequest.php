<?php

namespace App\Http\Requests\Backend\Discount;

use App\Http\Requests\FormRequest;

class DiscountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $pregex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        return [
            'price' => ['required','reges:'.$pregex],
            'discount' => 'required|numeric'
        ];
    }
}
