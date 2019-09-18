<?php

namespace Quantum\tickets\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Rules\Recaptcha;

class Create_Ticket_Request extends FormRequest
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
        $rules = [
            'department' => 'required|integer|exists:ticket_department,id',
            'subject' => 'string',
            'content' => 'string',
            'email' => 'email',
        ];
        if(\Settings::get('recaptcha_guest_ticket') && \Settings::get('recaptcha_site_key') != '')
        {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha()];
        }

        return $rules;

    }

    public function sanitize()
    {
        $input = $this->all();
        $input['subject'] = filter_var($input['subject'], FILTER_SANITIZE_STRING);
        $input['content'] = filter_var($input['content'], FILTER_SANITIZE_STRING);
        $input['email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL);

        $this->replace($input);
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Recaptcha not clicked !'
        ];
    }
}
