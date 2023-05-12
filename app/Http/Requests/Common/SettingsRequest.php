<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'company' => 'required|max:50',
            'company_email' => 'required|email',
            'website' => 'required|url',
            'phone' => 'required',
            'address' => 'required',
            'state' => 'required',
            'country' => 'required',
            'default_currency' => 'required',
            'admin-logo' => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
            'fav-icon' => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
            'logo' => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
        ];
    }

    public function messages()
    {
        return[
            'company.required'           => 'The Company name field is required',
            'company.max'                => 'The Company name must not be greater than 50 characters',
            'logo.mimes'                 => 'The Client Panel Logo must be a file of type: jpeg, jpg, png, gif',
            'admin-logo.mimes'           =>  'The Admin Panel Logo must be a file of type: jpeg, jpg, png, gif',
            'fav-icon.mimes'             => 'The Favicon must be a file of type: jpeg, jpg, png, gif',
        ];
    }
}
