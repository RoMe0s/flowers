<?php

namespace App\Http\Requests\Backend\Banner;

use App\Http\Requests\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route()->parameter('banner');

        $rule = "required|unique:banners";

        $rule .= isset($id) ? ",id," . $id : "";

        $rules = [
            'layout_position' => $rule,
            'status' => 'required|boolean'
        ];

        $item_rules = [
            'items.new.*.image'     => 'required',
            'items.new.*.status'   => 'required|boolean',
            'items.new.*.position' => 'required|integer',
            'items.old.*.image'     => 'required',
            'items.old.*.status'   => 'required|boolean',
            'items.old.*.position' => 'required|integer'
        ];

        $item_language_rules = [
            'name' => 'required'
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($item_language_rules as $name => $rule) {
                $items_rules['items.new.*.'.$locale.'.'.$name] = $rule;
                $items_rules['items.old.*.'.$locale.'.'.$name] = $rule;
            }
        }

        return array_merge($rules, $item_rules);
    }
}
