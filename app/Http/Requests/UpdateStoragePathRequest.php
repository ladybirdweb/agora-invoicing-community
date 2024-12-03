<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoragePathRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'disk' => 'required|string',
            'path' => 'string|nullable',
        ];
    }

    public function messages()
    {
        return [
            'disk.required' => trans('message.disk_required'),
        ];
    }
}
