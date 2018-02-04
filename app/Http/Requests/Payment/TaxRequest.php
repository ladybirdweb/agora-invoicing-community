<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;

class TaxRequest extends Request
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
            'name'      => 'required',
            'rate'      => 'required|numeric',
            'level'     => 'required|integer',
            'country'   => 'required',
            'state'     => 'required',
            // 'country' => 'exists:countries,country_id',
            // 'state'   => 'exists:states,state_subdivision_id',
        ];
    }
}
