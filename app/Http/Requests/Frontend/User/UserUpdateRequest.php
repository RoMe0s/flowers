<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\FormRequest;
use App\Models\UserInfo;
use Sentry;

/**
 * Class UserUpdateRequest
 * @package App\Http\Requests\Frontend\User
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

        $rules = [
            'email' => 'email|unique:users',
            'phone' => 'string|regex:' . config('user.phone_regex')
        ];

        return $rules;
    }
}