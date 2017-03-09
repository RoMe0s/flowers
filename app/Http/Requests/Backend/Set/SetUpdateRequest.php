<?php

namespace App\Http\Requests\Backend\Set;

use App\Http\Requests\FormRequest;

class SetUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('set');

        $regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';

        $pregex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        $rules = [
            'position'  => 'required',
            'status'   => 'required|boolean',
            'slug'     => 'required|unique:products,slug,'.$id.',id',
            'price' => ['required', 'regex:'.$pregex],
            'image'    => ['regex:'.$regex],
            'category_id'    => 'required',
            'box_id'   => 'required',
            'flowers' => 'required'
        ];

        $languageRules = [
            'name' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        return $rules;
    }
}
