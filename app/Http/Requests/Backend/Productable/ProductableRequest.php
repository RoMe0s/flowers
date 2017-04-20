<?php

namespace App\Http\Requests\Backend\Productable;

use App\Http\Requests\FormRequest;

class ProductableRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
		'productable_id' => 'required|integer',
		'productable_type' => 'required',
		'status' => 'required|boolean',
		'position' => 'required|integer'
        ];
    }
}
