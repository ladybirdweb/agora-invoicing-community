<?php

namespace App\Http\Requests\Email;

use Illuminate\Foundation\Http\FormRequest;

class EmailSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->driver == 'smtp') {
            return [
                'driver' => 'required',
                'email' => 'required',
                'password' => 'required',
                'port' => 'required',
                'encryption' => 'required',
                'host' => 'required',
            ];
        } elseif ($this->driver == 'mailgun') {
            return [
                'driver' => 'required',
                'email' => 'required',
                //                'password' => 'required',
                'secret' => 'required',
                'domain' => 'required',
            ];
        } elseif ($this->driver == 'mandrill') {
            return [
                'driver' => 'required',
                'email' => 'required',
                'password' => 'required',
                'secret' => 'required',
            ];
        } elseif ($this->driver == 'ses') {
            return [
                'driver' => 'required',
                'email' => 'required',
                'password' => 'required',
                'secret' => 'required',
                'key' => 'required',
                'region' => 'required',
            ];
        } elseif ($this->driver == 'sparkpost') {
            return [
                'driver' => 'required',
                'email' => 'required',
                'password' => 'required',
                'secret' => 'required',
            ];
        } else {
            return [
                'driver' => 'required',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        $emailDomain = explode('@', $value)[1];
                        $url = \Request::url();
                        $domain = parse_url($url);
                        if (strcasecmp($domain['host'], $emailDomain) !== 0) {
                            return $fail('The email domain does not match the URL domain.');
                        }
                    },
                ],
            ];
        }
    }
}
