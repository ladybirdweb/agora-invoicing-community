<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\Request;

class CheckoutRequest extends Request
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
        //dd($this->method() );
        if ($this->method() == 'POST') {
            return [
                'first_name' => 'required',
                'last_name'  => 'required',
                'company'    => 'required',
                'mobile'     => 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                'address'    => 'required',
                'zip'        => 'required|min:5|numeric',
                'email'      => 'required|email|unique:users,email',
                //'payment_gateway' => 'required',
            ];
        } elseif ($this->method() == 'PATCH') {
            return [
                'first_name' => 'required',
                'last_name'  => 'required',
                'company'    => 'required',
                'mobile'     => 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                'address'    => 'required',
                'zip'        => 'required|min:5|numeric',
                'email'      => 'required|email',
                //'payment_gateway' => 'required',
            ];
        }
    }

    public function messages()
    {
        return[
            'payment_gatway.required' => 'Choose one payment gateway',
        ];
    }
}
