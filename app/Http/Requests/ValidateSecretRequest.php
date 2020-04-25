<?php

namespace App\Http\Requests;

use Cache;
use Crypt;
use Google2FA;
use App\User;
use App\Http\Requests\Request;
use Illuminate\Validation\Factory as ValidatonFactory;

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