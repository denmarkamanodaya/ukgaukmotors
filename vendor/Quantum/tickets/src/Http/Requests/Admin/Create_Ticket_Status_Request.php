<?php

namespace Quantum\tickets\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class Create_Ticket_Status_Request extends FormRequest
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
            'name' => 'required|string',
            'description' => 'string',
            'icon' => 'string',
            'default' => 'boolean',
        ];
    }
}
