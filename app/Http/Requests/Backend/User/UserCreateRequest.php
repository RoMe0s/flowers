<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\User;

use App\Decorators\Phone;
use App\Http\Requests\FormRequest;

/**
 * Class UserCreateRequest
 * @package App\Http\Requests\Backend\User
 */
class UserCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'email'                 => 'email',
            'phone'                 => 'required|unique:users,login',
            'password'              => 'required',
            'groups'                => 'array',
            'activated'             => 'required|boolean',
            'notifications'         => 'required|boolean',
            'start_discount'        => 'integer',
            'discount'              => 'integer'
        ];

        $phone = new Phone($this->request->get('phone'));

        $phone = $phone->getDecorated();

        $this->merge(['login' => $phone]);

        $this->merge(['phone' => $phone]);

        return array_merge($rules, app('App\Http\Requests\Backend\User\UserInfoRequest')->rules());
    }
}
