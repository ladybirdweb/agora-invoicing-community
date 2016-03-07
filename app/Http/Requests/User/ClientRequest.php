<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class ClientRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        switch ($this->method()) {
            case 'POST' : {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'email' => 'required|email|unique:users',
                        'company' => 'required',
                        'mobile' => 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                        'address' => 'required',
                        'zip' => 'required|min:5|numeric',
                    ];
                }

            case 'PATCH' : {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'email' => 'required|email|unique:users,email,' . $this->getSegmentFromEnd() . ',id',
                        'company' => 'required',
                        'mobile' => 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                        'address' => 'required',
                        'zip' => 'required|min:5|numeric',
                    ];
                }
            default :break;
        }
    }

    private function getSegmentFromEnd($position_from_end = 1) {
        $segments = $this->segments();
        return $segments[sizeof($segments) - $position_from_end];
    }

}
