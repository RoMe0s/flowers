<?php

namespace App\Http\Requests\Frontend\Code;

use App\Http\Requests\FormRequest;

class CodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|min:3|max:10'
        ];
    }
}
