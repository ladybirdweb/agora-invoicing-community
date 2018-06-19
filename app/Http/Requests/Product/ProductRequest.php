<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;

class ProductRequest extends Request
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
                'name'              => 'required',
                'type'              => 'required',
                'group'             => 'required',
                'subscription'      => 'required',
                'currency'          => 'required',
                // 'price'             => 'required',
                'file'              => 'required_without_all:github_owner,github_repository|mimes:zip',
                'image'             => 'required_without_all:github_owner,github_repository|mimes:png',
                'github_owner'      => 'required_without_all:file,image',
                'github_repository' => 'required_without_all:file,image|required_if:type,2',

            ];
    }
}
