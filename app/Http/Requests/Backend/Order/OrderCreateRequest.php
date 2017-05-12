<?php

namespace App\Http\Requests\Backend\Order;

use App\Http\Requests\FormRequest;

class OrderCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $pregex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        $user_id = $this->input('user_id', null);

        $address_id = $this->input('address_id', null);

        $prepay = $this->input('prepay');

        if(empty($user_id)) {

            $this->request->remove('user_id');

        }

        if(empty($address_id)) {

            $this->request->remove('address_id');

        }

        if($prepay == 100) {

            $this->merge(['user_data_required' => true]);

        }

        $new_user = $this->input('user', []);

        $new_user = array_filter($new_user);

        if(!sizeof($new_user)) {

            $this->request->remove('user');

        }

        $individual = $this->input('individual', []);

        $individual = array_filter($individual);

        if(!sizeof($individual)) {

            $this->request->remove('individual');

        }

        $rules = [
            'courier_id' => 'integer',
            'delivery_price' => 'required',
            'discount'      => 'integer|required',
            'prepay'    => 'required',
            'recipient_name' => 'required_with:user_data_required',
            'recipient_phone'    => 'required_with:user_data_required|string|regex:' . config('user.phone_regex'),
            'email' => 'email',
            'date' => 'date',
            'status' => 'required',
            'card_text' => 'max:150',
            'address' => 'required'
        ];

        $items_rules = [
            'items.*.price'     => ['required', 'regex:' . $pregex],
            'items.*.count'     => 'required',
            'items.*.itemable_id'   => 'required|integer',
            'items.*.itemable_type' => 'required|string',
        ];

        $user_rules = [
            'user.phone' => 'required|string'
        ];

        $individual_rules = [
            'individual.price' => ['required', 'regex:' . $pregex],
            'individual.text' => 'required'
        ];

        if(empty($user_id)) {

            $rules = array_merge($rules, $user_rules);
        }

        $items = $this->input('items', []);

        if(!sizeof($items)) {

            $rules = array_merge($rules, $individual_rules);

        }

        return array_merge($rules, $items_rules);
    }
}
