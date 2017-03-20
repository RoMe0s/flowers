<?php

namespace App\Http\Requests\Backend\Order;

use App\Http\Requests\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $pregex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        $user_id = $this->input('user_id');
        $address_id = $this->input('address_id');

        if($user_id < 0) {
            $this->request->remove('user_id');
        }
        if($address_id < 0) {
            $this->request->remove('address_id');
        }

        $rules = [
            'courier_id' => 'required',
            'delivery_price' => 'required',
            'discount'      => 'required',
            'prepay'    => 'required',
            'recipient_name' => 'required',
            'recipient_phone'    => 'required|string|regex:/^\+[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'email' => 'required|email',
            'date' => 'date',
            'status' => 'required',
            'password' => 'required_without:user_id',
            'card_text' => 'max:150'
        ];

        if(!$this->request->has('address_need')) {

            $rules = [
                'address' => 'required',
                'code'    => 'required'
            ];

        }

        $items_rules = [
            'items.new.*.price'     => ['required', 'regex:' . $pregex],
            'items.new.*.count'     => 'required',
            'items.new.*.itemable_id'   => 'required|integer',
            'items.new.*.itemable_type' => 'required|string',
            'items.old.*.price'     => ['required', 'regex:' . $pregex],
            'items.old.*.count'     => 'required',
            'items.old.*.itemable_id'   => 'required|integer',
            'items.old.*.itemable_type' => 'required|string',
        ];

        return array_merge($rules, $items_rules);
    }
}
