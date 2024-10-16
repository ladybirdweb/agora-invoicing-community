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
            'product_storage' => 'required_if:disk,system',
            'path' => 'string|nullable',
            's3_bucket' => 'required_if:disk,s3|string',
            's3_region' => 'required_if:disk,s3|string',
            's3_access_key' => 'required_if:disk,s3|string',
            's3_secret_key' => 'required_if:disk,s3|string',
            's3_endpoint_url' => 'required_if:disk,s3|string',
        ];
    }

    public function messages()
    {
        return [
            'disk.required' => trans('message.disk_required'),
            'product_storage.required_if' => trans('message.product_storage_required'),
            's3_bucket.required_if' => trans('message.s3_bucket_required'),
            's3_region.required_if' => trans('message.s3_region_required'),
            's3_access_key.required_if' => trans('message.s3_access_key_required'),
            's3_secret_key.required_if' => trans('message.s3_secret_key_required'),
            's3_endpoint_url.required_if' => trans('message.s3_endpoint_url_required'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('product_storage') === 'system' && $this->input('disk') === 'system') {
                if (empty($this->input('path'))) {
                    $validator->errors()->add('path', trans('message.path_required'));
                }
            }
        });
    }
}
