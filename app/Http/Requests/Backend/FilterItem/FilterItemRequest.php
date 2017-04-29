<?php

namespace App\Http\Requests\Backend\FilterItem;

use App\Http\Requests\FormRequest;

class FilterItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route()->parameter('product');

        $slug_rule = 'unique:products';

        if($id) {

            $slug_rule = 'unique:filter_items,slug,'.$id.',id';

        }

        $rules = [
            'status' => 'required|boolean',
            'position' => 'required|integer',
            'value' => 'required',
            'type' => 'required',
            'slug' => $slug_rule
        ];

        $languageRules = [
            'title' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        return $rules;
    }
}
