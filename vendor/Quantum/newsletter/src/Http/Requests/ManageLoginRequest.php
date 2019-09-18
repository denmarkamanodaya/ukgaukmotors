<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Rules\Recaptcha;

class ManageLoginRequest extends FormRequest
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
        $this->sanitize();

        if(\Settings::get('recaptcha_guest_newsletter') && \Settings::get('recaptcha_site_key') != '')
        {
            return [
                'email' => 'required|email',
                'g-recaptcha-response' => ['required', new Recaptcha()]
            ];
        } else {
            return [
                'email' => 'required|email'
            ];
        }

    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['email'])) $input['email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $this->replace($input);
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Recaptcha not clicked !'
        ];
    }
}
