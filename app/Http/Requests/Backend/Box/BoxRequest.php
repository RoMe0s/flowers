<?php

namespace App\Http\Requests\Backend\Box;

use App\Http\Requests\FormRequest;

class BoxRequest extends FormRequest
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
            'category_id'   => 'required',
            'length'    => 'required',
            'width'     => 'required',
            'image'    => ['regex:'.$regex],
        ];

        $languageRules = [
            'title' => 'required'
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        return $rules;
    }
}
