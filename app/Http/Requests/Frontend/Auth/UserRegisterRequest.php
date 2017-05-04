<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\Auth;

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
            'phone'                 => 'unique:user_info|required|string|regex:/^[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'agreement'             => 'required'
        ];

        $this->merge(['login' => $this->request->get('phone')]);
        
        return $rules;
    }
}