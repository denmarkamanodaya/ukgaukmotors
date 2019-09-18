<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTemplateRequest extends FormRequest
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
            'title' => 'required|string',
            'content' => 'required|string',
            'template_type' => 'required|in:theme,template'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['title'])) $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING);
        $this->replace($input);
    }
}
