<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTemplateRequest extends FormRequest
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
            'newsletter_template' => 'required|string'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['newsletter_template'])) $input['newsletter_template'] = filter_var($input['newsletter_template'], FILTER_SANITIZE_STRING);
        $this->replace($input);
    }
}
