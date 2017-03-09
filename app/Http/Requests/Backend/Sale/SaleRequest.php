<?php

namespace App\Http\Requests\Backend\Sale;

use App\Http\Requests\FormRequest;

class SaleRequest extends FormRequest
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
            'image'    => ['regex:'.$regex],
            'price'    => ['required', 'regex:'.$pregex],
            'publish_at' => ['required', 'date_format:d-m-Y'],
            'status'    => 'required|boolean',
            'position'  => 'required'
        ];
    }
}
