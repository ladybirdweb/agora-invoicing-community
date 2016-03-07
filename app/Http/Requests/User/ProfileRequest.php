<?php namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class ProfileRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return TRUE;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
            //dd($this->segment(1));
            if($this->segment(1)=="profile")
            {
		return [
                    'first_name'=>'required',
                    'last_name'=>'required',
                    'company'=>'required',
                    'mobile'=>'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                    'address'=>'required',
                    'zip'=>'required|min:5|numeric',
                    
		];
            }
            if($this->segment(1)=="password")
            {
                return [
                    'old_password'=>'required|min:6',
                    'new_password'=>'required|min:6',
                    'confirm_password'=>'required|same:new_password',
		];
            }
            if($this->segment(1)=="auth")
            {
                return [
                    'first_name'=>'required',
                    'last_name'=>'required',
                    'company'=>'required',
                    'mobile'=>'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                    //'address'=>'required',
                    'terms'=>'accepted',
                    'zip'=>'required|min:5|numeric',
                    'password'=>'required|min:6',
                    'password_confirmation'=>'required|same:password',
                    
		];
            }
	}

}
