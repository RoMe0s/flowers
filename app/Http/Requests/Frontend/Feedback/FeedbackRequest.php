<?php

namespace App\Http\Requests\Frontend\Feedback;

use App\Http\Requests\FormRequest;

/**
 * Class FeedbackRequest
 * @package App\Http\Requests\Frontend\Feedback
 */
class FeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required',
            'email'   => 'required|email',
            'phone'    => 'string|regex:' . config('user.phone_regex'),
            'text' => 'required',
        ];
    }
}
