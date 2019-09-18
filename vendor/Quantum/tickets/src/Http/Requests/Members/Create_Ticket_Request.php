<?php

namespace Quantum\tickets\Http\Requests\Members;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'department' => 'required|integer|exists:ticket_department,id',
            'subject' => 'string',
            'content' => 'string',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['subject'] = filter_var($input['subject'], FILTER_SANITIZE_STRING);
        $input['content'] = filter_var($input['content'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
