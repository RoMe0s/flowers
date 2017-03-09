<?php

namespace App\Http\Requests\Backend\Code;

use App\Http\Requests\FormRequest;

class CodeUpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route()->parameter('code');

        return [
            'status'    => 'required|boolean',
            'code' => 'required|unique:codes,' . $id . ',id',
            'discount'  => 'required|numeric',
            'date'      => 'required'
        ];
    }
}
