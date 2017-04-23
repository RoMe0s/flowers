<?php

namespace App\Http\Requests\Backend\MainPageMenu;

use App\Http\Requests\FormRequest;

class MainPageMenuRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|boolean',
            'position' => 'required|integer',
            'menuable_id' => 'required|integer',
            'menuable_type' => 'required'
        ];
    }
}
