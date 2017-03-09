<?php

namespace App\Http\Requests\Backend\Code;

use App\Http\Requests\FormRequest;

class CodeCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status'    => 'required|boolean',
            'code' => 'required|unique:codes',
            'discount'  => 'required|numeric',
            'date'      => 'required'
        ];
    }
}
