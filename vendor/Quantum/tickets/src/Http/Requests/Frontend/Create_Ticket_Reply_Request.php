<?php

namespace Quantum\tickets\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Rules\Recaptcha;


class Create_Ticket_Reply_Request extends FormRequest
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
            'content' => 'string',
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
        $input['content'] = filter_var($input['content'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Recaptcha not clicked !'
        ];
    }
}
