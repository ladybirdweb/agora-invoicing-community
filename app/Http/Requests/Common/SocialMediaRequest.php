<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaRequest extends FormRequest
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
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        if ($this->method() == 'POST') {
            
            return [
                'name' => 'required|unique:social_media',
               'link' => 'required|url|regex:'.$regex,
            ];
          }
        elseif ($this->method() == 'PATCH'){
            return [
               'name' => 'required',
               'link' => 'required|url|regex:'.$regex,
            ];
        }
 
    }
}
