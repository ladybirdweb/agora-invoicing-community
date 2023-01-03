<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return [
                'name' => 'required|unique:frontend_pages,name',
                'publish' => 'required',
                'slug' => 'required',
                'url' => 'required',
                'content' => 'required',
            ];
        } elseif ($this->method() == 'PATCH') {
            return [
                'name' => 'required',
                'publish' => 'required',
                'slug' => 'required',
                'url' => 'required',
                'content' => 'required',
                'created_at' => 'required',
            ];
        }
    }

    public function messages()
    {
        return[
            'created_at.required'           => 'Publish Date is required',
        ];
    }
}
