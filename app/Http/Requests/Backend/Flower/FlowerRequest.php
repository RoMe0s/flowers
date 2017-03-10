<?php

namespace App\Http\Requests\Backend\Flower;

use App\Http\Requests\FormRequest;

class FlowerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status'      => 'required|boolean',
            'colors'      => 'required'
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
