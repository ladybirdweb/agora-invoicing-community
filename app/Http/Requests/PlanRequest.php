<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required',
            'country_id'            => 'required|array',
            'currency'              => 'required|array|array_size_equals:country_id',
            'renew_price'           => 'required|array|array_size_equals:country_id',
            'add_price'             => 'required|array|array_size_equals:country_id',
            'country_id.*'          => 'required_with:country_id|numeric|duplicate_country:currency',
            'currency.*'            => 'required_with:currency',
            'add_price.*'           => 'required_with_all:country_id,currency|integer|min:0',
            'renew_price.*'         => 'required_with_all:country_id,currency|integer|min:0',
            'product'               => 'required',
            'days'                  => 'nullable|numeric',
            'product_quantity'      => 'required_without:no_of_agents|integer|min:0',
            'no_of_agents'          => 'required_without:product_quantity|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'country_id.*.required'      => trans('message.country_missing'),
            'add_price.*.numeric'        => trans('message.regular_price_numeric'),
            'add_price.*.required_with_all' => trans('message.add_price_required'),
            'renew_price.*.required_with_all' => trans('message.renew_price_required'),
            'renew_price.*.numeric'      => trans('message.renew_price_numeric'),
            'country_id.*.duplicate_country' => trans('message.duplicate_country'),
            'currency.array_size_equals' => trans('message.currency_array_equals'),
            'renew_price.array_size_equals' => trans('message.renew_price_array_equals'),
            'add_price.array_size_equals' => trans('message.add_price_array_equals'),
            'currency.*.required_with' => trans('message.currency_missing'),
        ];
    }
}
