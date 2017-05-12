<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\Auth;

use App\Decorators\Phone;
use App\Http\Requests\FormRequest;
use App\Models\UserInfo;


/**
 * Class UserRegisterRequest
 * @package App\Http\Requests\Frontend\Auth
 */
class UserRegisterRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'email'                 => 'email|unique:users',
            'phone'                 => 'unique:user_info|required|string|regex:' . config('user.phone_regex'),
            'agreement'             => 'required'
        ];

        $phone = new Phone($this->request->get('phone'));

        $phone = $phone->getDecorated();

        $this->merge(['login' => $phone]);
        
        return $rules;
    }
}