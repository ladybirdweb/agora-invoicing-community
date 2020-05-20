<?php

namespace App\Http\Requests;

class ValidateSecretRequest extends Request
{
    public function rules()
    {
        return [
            'totp' => 'bail|required|digits:6',
        ];
    }

    public function messages()
    {
        return[
            'totp.required' => 'Please enter code',
        ];
    }
}
