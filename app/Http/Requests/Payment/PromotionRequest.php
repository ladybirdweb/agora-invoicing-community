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
            'uses' => 'required',
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
}
