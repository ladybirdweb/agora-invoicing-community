<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;

class GroupRequest extends Request
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
            'name'            => 'required',
            // 'features.*.name' => 'required',
            // 'title'           => 'required_with:type,price,value',
            // 'type'            => 'required_with:title,price,value',
            // 'price.*.name'    => 'required_unless:type,1|numeric',
            // 'price.*.name'    => 'required_unless:type,2|numeric',
            // 'value.*.name'    => 'required_unless:type,1',
            // 'value.*.name'    => 'required_unless:type,2',
        ];
    }

    public function messages()
    {
        return[
                'name.required'                => 'Name is required',
                'features.*.name.required'     => 'All Features Field Required',
                'price.*.name.required_unless' => 'Price is required',
                'value.*.name.required_unless' => 'Value is required',
                'type.required_with'           => 'Type is required',
                'title.required_with'          => 'Title is required',

        ];
    }
}
