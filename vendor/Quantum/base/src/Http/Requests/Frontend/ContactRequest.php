<?php

namespace Quantum\base\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $input['subject'] = filter_var($input['subject'], FILTER_SANITIZE_STRING);
        $input['message'] = filter_var($input['message'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
