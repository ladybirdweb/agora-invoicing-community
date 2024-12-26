<?php

namespace App\Http\Requests\Front;

use App\Rules\CaptchaValidation;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->is('contact-us')) {
            return [
                'conName' => 'required',
                'email' => 'required|email',
                'conmessage' => 'required',
                'Mobile' => 'required',
                'country_code' => 'required',
                'congg-recaptcha-response-1' => [isCaptchaRequired()['is_required'], new CaptchaValidation()],
            ];
        } elseif ($this->is('demo-request')) {
            return [
                'demoname' => 'required',
                'demoemail' => 'required|email',
                'country_code' => 'required',
                'Mobile' => 'required',
                'demomessage' => 'required',
                'demo-recaptcha-response-1' => [isCaptchaRequired()['is_required'], new CaptchaValidation()],
            ];
        }
    }

    public function messages()
    {
        return[
            'conName' => 'The name field is required',
            'email' => 'The email field is required',
            'conmessage' => 'The message field is required',
            'Mobile' => 'The Mobile field is required',
            'country_code' => 'The Mobile field is required',
            'demoname' => 'The name field is required',
            'demomessage' => 'The message field is required',
            'demoemail' => 'The email field is required',
            'congg-recaptcha-response-1.required' => 'Robot Verification Failed. Please Try Again.',
            'demo-recaptcha-response-1.required' => 'Robot Verification Failed. Please Try Again.',
        ];
    }
}
