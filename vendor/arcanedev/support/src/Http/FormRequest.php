<?php namespace Arcanedev\Support\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

/**
 * Class     FormRequest
 *
 * @package  Arcanedev\Support
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class FormRequest extends BaseFormRequest
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge($this->sanitize());
    }

    /**
     * Sanitize the inputs after the validation.
     *
     * @return array
     */
    protected function sanitize()
    {
        return [
            //
        ];
    }
}
