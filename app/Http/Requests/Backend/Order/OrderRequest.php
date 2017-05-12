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
        $prepay = $this->input('prepay');

        if($user_id < 0) {
            $this->request->remove('user_id');
        }
        if($address_id < 0) {
            $this->request->remove('address_id');
        }
        if($prepay == 100) {
            $this->merge(['user_data_required' => true]);
        } else {

            $recipient_name = $this->input('recipient_name', null);

            $recipient_phone = $this->input('recipient_phone', null);

            if(empty($recipient_name)) {

                $this->replace(['recipient_name' => $recipient_name]);

            }

            if(empty($recipient_phone)) {

                $this->replace(['recipient_phone' => $recipient_phone]);

            }

        }

        $rules = [
            'courier_id' => 'integer',
            'delivery_price' => 'required',
            'discount'      => 'integer',
            'prepay'    => 'required',
            'recipient_name' => 'required_with:user_data_required',
            'recipient_phone'    => 'required_with:user_data_required|string|regex:' . config('user.phone_regex'),
            'email' => 'email|required_with:email_required',
            'date' => 'date',
            'status' => 'required',
            'password' => 'required_without:user_id',
            'card_text' => 'max:150'
        ];

        if(!$this->request->has('address_need')) {

            $rules += [
                'address' => 'required'
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
