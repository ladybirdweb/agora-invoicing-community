<?php namespace App\Http\Requests\Common;

use App\Http\Requests\Request;

class SettingRequest extends Request {

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
		return [
                    'company'=>'required',
                    'website'=>'url',
                    'phone'=>'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                    'address'=>'required|max:100',
                    'logo'=>'mimes:png',
                    'driver'=>'required',
                    'port'=>'integer',
                    'email'=>'required|email',
                    'password'=>'required|min:6',
                    'error_email'=>'email'
                    
		];
	}

}
