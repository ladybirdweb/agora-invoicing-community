<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class ClientRequest extends Request
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
        switch ($this->method()) {
            case 'POST': {
                    return [
                        'first_name' => 'required',
                        'last_name'  => 'required',
                        'email'      => 'required|email|unique:users',
                        'company'    => 'required',
                        'mobile'     => 'required|numeric',
                    'mobile_code'     => 'required|numeric',
                        'address'    => 'required',
                        'zip'        => 'required|min:5|numeric',
                    ];
                }

            case 'PATCH': {
                    return [
                        'first_name' => 'required',
                        'last_name'  => 'required',
                        'email'      => 'required|email|unique:users,email,'.$this->getSegmentFromEnd().',id',
                        'company'    => 'required',
                        'mobile'     => 'required|numeric',
                    'mobile_code'     => 'required|numeric',
                        'address'    => 'required',
                        'zip'        => 'required|min:5|numeric',
                    ];
                }
            default:break;
        }
    }

    private function getSegmentFromEnd($position_from_end = 1)
    {
        $segments = $this->segments();

        return $segments[count($segments) - $position_from_end];
    }
}
