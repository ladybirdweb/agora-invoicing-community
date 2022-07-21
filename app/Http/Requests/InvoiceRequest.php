<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
        return [
            'user' => 'required',
            'date' => 'required|date',
            'domain' => 'sometimes|nullable|regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i',
            'plan' => 'required_if:subscription,true',
            'price' => 'required',
            'product' => 'required',
        ];
    }

    public function messages()
    {
        return[
            'plan.required_if' => 'The subscription field is required',
            'user.required' => 'The clients field is required',
        ];
    }
}
