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
            's3_bucket.required_if' => trans('message.s3_bucket_required'),
            's3_region.required_if' => trans('message.s3_region_required'),
            's3_access_key.required_if' => trans('message.s3_access_key_required'),
            's3_secret_key.required_if' => trans('message.s3_secret_key_required'),
            's3_endpoint_url.required_if' => trans('message.s3_endpoint_url_required'),
        ];
    }
}
