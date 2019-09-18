<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriberNewsletter extends FormRequest
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
            'first_name' => 'nullable|alpha',
            'last_name' => 'nullable|alpha',
            'name' => 'nullable|string|max:100',
            'email' => 'required|email',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['first_name'])) $input['first_name'] = filter_var($input['first_name'], FILTER_SANITIZE_STRING);
        if(isset($input['last_name'])) $input['last_name'] = filter_var($input['last_name'], FILTER_SANITIZE_STRING);
        if(isset($input['name'])) $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
        if(isset($input['email'])) $input['email'] = filter_var($input['email'], FILTER_SANITIZE_STRING);
        $this->replace($input);
    }
}
