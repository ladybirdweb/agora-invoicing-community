<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;

class PromotionRequest extends Request
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
        $rules = [
            'code' => 'required',
            'type' => 'required',
            'applied' => 'required',
            'uses' => 'required|numeric',
            'start' => 'required',
            'expiry' => 'required|after:start',
        ];
        // If 'type' is 'percentage', add additional validation for 'value'
        if ($this->input('type') === '1') {
            $rules['value'] = 'required|numeric|between:1,100';
        } else {
            $rules['value'] = 'required|numeric';
        }

        return $rules;
    }

    public function messages()
    {
    return [
        'code.required' => 'The coupon code field is required.',
        'code.string' => 'The coupon code must be a string.',
        'code.max' => 'The coupon code must not exceed 255 characters.',
        'type.required' => 'The type field is required.',
        'type.in' => 'Invalid type. Allowed values are: percentage, other_type.',
        'applied.required' => 'The applied for a product field is required.',
        'applied.date' => 'The applied for a product field must be a valid date.',
        'uses.required' => 'The uses field is required.',
        'uses.numeric' => 'The uses field must be a number.',
        'uses.min' => 'The uses field must be at least :min.',
        'start.required' => 'The start field is required.',
        'start.date' => 'The start field must be a valid date.',
        'expiry.required' => 'The expiry field is required.',
        'expiry.date' => 'The expiry field must be a valid date.',
        'expiry.after' => 'The expiry date must be after the start date.',
        'value.required' => 'The discount value field is required.',
        'value.numeric' => 'The discount value field must be a number.',
        'value.between' => 'The discount value field must be between :min and :max if the type is percentage.',
    ];
}
}
