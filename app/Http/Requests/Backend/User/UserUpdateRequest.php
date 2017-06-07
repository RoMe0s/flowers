<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Requests\Backend\User;

use App\Http\Requests\FormRequest;

/**
 * Class UserUpdateRequest
 * @package App\Http\Requests\Backend\User
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('user');

        $rules = [
            'email'     => 'required|email',
            'activated' => 'required|boolean',
            'notifications'         => 'required|boolean',
            'start_discount'        => 'integer',
            'discount'              => 'integer'
        ];

        return array_merge($rules, app('App\Http\Requests\Backend\User\UserInfoRequest')->rules());
    }
}