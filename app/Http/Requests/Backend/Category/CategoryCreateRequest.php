<?php

namespace App\Http\Requests\Backend\Category;

use App\Http\Requests\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';

        $rules = [
            'image'    => ['regex:'.$regex],
            'slug'     => 'required|unique:categories',
            'status'   => 'required|boolean',
            'position' => 'required|integer',
            'type'     => 'required',
            'parent_id' => 'integer'
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
