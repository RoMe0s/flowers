<?php

namespace App\Http\Requests\Backend\Subscription;

use App\Http\Requests\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regex = '/^.*\.('.implode('|', config('image.allowed_image_extension')).')$/';

        $pregex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        $rules = [
            'position'  => 'required',
            'status'   => 'required|boolean',
            'price' => ['required','regex:' . $pregex],
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
