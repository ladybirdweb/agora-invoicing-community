<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
class verifyOtp extends FormRequest
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
        $email = $this->request->get('newemail');
        $pass = User::where('email',$email)->value('password');
       
       
       return [
             'verify_email'   => 'sometimes|required|verify_email|email',
            'verify_email'   => 'sometimes|required||verify_country_code|numeric',
            'verify_email'   => 'sometimes|required|verify_number|numeric',
            'password' => [
               
                function($attribute, $value, $fail) use($pass)
                {
                    
                   
                if(!Hash::check($value,$pass))
                {
                 return $fail('Invalid Password');   
                }
                },
                ],
        ];
        
    }
}
