<?php

namespace Quantum\base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecaptchaSettingsRequest extends FormRequest
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
            'recaptcha_site_key' => 'string',
            'recaptcha_secret_key' => 'string',
            'recaptcha_register' => 'boolean',
            'recaptcha_login' => 'boolean',
            'recaptcha_password' => 'boolean',
            'recaptcha_guest_ticket' => 'boolean',
            'recaptcha_guest_newsletter' => 'boolean',
        ];
    }
}
