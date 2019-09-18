<?php

namespace Quantum\tickets\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class Update_Ticket_Settings_Request extends FormRequest
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
            'ticket_email_from_address' => 'required|email',
            'ticket_public_received_page' => 'required|string'
        ];
    }
}
