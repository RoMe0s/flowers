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
        return [
            'address_id'    => 'required',
            'city' => 'required_if:address_id,0',
            'street' => 'required_if:address_id,0',
            'house' => 'required_if:address_id,0',
            'flat' => 'required_if:address_id,0',
            'date'  => 'required|date',
            'time'  => 'required',
            'prepay' => 'required|integer',
            'recipient_name' => 'required_if:prepay,100',
            'recipient_phone' => 'required_if:prepay,100',
            'agreement'     => 'required'
        ];
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
            'date.required' => 'Выберите дату доставки'
        ];
    }
}
