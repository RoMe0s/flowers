<?php

namespace App\Http\Requests\Backend\Sale;

use App\Http\Requests\FormRequest;

class SaleUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route()->parameter('sale');

        $regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';

        $pregex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        $rules = [
            'image'    => ['regex:'.$regex],
            'price'    => ['required', 'regex:'.$pregex],
            'slug'     => 'unique:sales,slug,'.$id.',id',
            'publish_at' => ['required', 'date_format:d-m-Y'],
            'status'    => 'required|boolean',
            'position'  => 'required'
        ];

        $languageRules = [
            'name' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        $items_rules = [
            'images.*.link'     => 'required',
        ];

        return array_merge($rules, $items_rules);
    }
}
