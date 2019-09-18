<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateResponderRequest extends FormRequest
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
            'subject' => 'required|string',
            'html_message' => 'required|string',
            'interval_amount' => 'required|numeric',
            'interval_type' => 'required|in:Minutes,Hours,Days,Weeks,Months,Years',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['subject'])) $input['subject'] = filter_var($input['subject'], FILTER_SANITIZE_STRING);
        if(isset($input['interval_amount'])) $input['interval_amount'] = filter_var($input['interval_amount'], FILTER_SANITIZE_STRING);
        if(isset($input['interval_type'])) $input['interval_type'] = filter_var($input['interval_type'], FILTER_SANITIZE_STRING);
        $this->replace($input);
    }
}
