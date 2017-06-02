<?php

namespace App\Http\Requests\Frontend\Order;

use App\Http\Requests\FormRequest;
use App\Http\Requests\Request;

class OrderStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $address = $this->request->get('address_string', null);

        $specify = $this->request->get('specify', null);

        if($address && $address === "Самовывоз") {

            $this->request->remove('specify');

            $this->request->add(["address" => $address]);

            $address_rules = [
                'address_string' => 'required'
            ];

        } else {

            $address_rules = [
                'address_id'    => 'required_without:address.city',
                'address.city' => 'required_without:address_id',
                'address.street' => 'required_without:address_id',
                'address.house' => 'required_without:address_id'
            ];

        }

        if($specify && !empty($specify)) {

            $address_rules = [
                "specify" => 'required'
            ];

        }

        $rules = [
            'date'  => 'required|date',
            'time'  => 'required',
            'prepay' => 'required|integer',
            'recipient_name' => 'required_if:prepay,100',
            'recipient_phone' => 'required_if:prepay,100|regex:' . config('user.phone_regex'),
            'agreement'     => 'required',
            'accuracy' => 'boolean',
            'night'     => 'boolean',
            'distance'  => 'integer',
            'anonymously' => 'boolean',
            'neighbourhood' => 'boolean'
        ];

        return array_merge($rules, $address_rules);
    }

    public function messages()
    {
        return [
            'address_id.required' => 'Выберите адрес доставки или укажите другой заполнив поля ниже',
            'prepay.required' => 'Выберите размер предоплаты',
            'city.required' => 'Поле "город" должно быть заполнено',
            'street.required' => 'Поле "улица" должно быть заполнено',
            'house.required' => 'Поле "дом" должно быть заполнено',
            'flat.required' => 'Поле "квартира" должно быть заполнено',
            'time.required' => 'Выберите время доставки',
            'date.required' => 'Выберите дату доставки',
            'address.distance.required' => 'Поле должно иметь числовой формат',
            'neighbourhood.boolean' => 'Поле "Оставить соседям" должно быть логического формата(Да, Нет)',
            'anonymously.boolean' => 'Поле "Анонимно" должно быть логического формата(Да, Нет)',
            'specify.required_without' => 'Поле должно быть заполнено',
            'recipient_phone.regex' => 'Поле телефон имеет ошибочный формат'
        ];
    }
}
