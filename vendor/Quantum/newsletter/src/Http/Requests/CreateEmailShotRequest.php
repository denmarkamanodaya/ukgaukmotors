<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmailShotRequest extends FormRequest
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
        return [
            'newsletter' => 'required|exists:newsletter,id',
            'subject' => 'required|string',
            'html_message' => 'required|string',
            'send_on' => 'date',
            'personalise' => 'nullable|integer|in:1',
            'bcc_amount' => 'nullable|integer|min:0',
            'bcc_to_email' => 'nullable|email'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['newsletter'])) $input['newsletter'] = filter_var($input['newsletter'], FILTER_SANITIZE_STRING);
        if(isset($input['subject'])) $input['subject'] = filter_var($input['subject'], FILTER_SANITIZE_STRING);
        if(isset($input['send_on'])) $input['send_on'] = filter_var($input['send_on'], FILTER_SANITIZE_STRING);
        if(isset($input['personalise'])) $input['personalise'] = filter_var($input['personalise'], FILTER_SANITIZE_STRING);
        if(isset($input['bcc_amount'])) $input['bcc_amount'] = filter_var($input['bcc_amount'], FILTER_SANITIZE_STRING);
        if(isset($input['bcc_to_email'])) $input['bcc_to_email'] = filter_var($input['bcc_to_email'], FILTER_SANITIZE_EMAIL);
        $this->replace($input);
    }
}
