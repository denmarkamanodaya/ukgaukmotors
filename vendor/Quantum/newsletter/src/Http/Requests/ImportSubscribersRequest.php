<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportSubscribersRequest extends FormRequest
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
            'importcsv' => 'required|mimes:csv,txt',
            'send_welcome' => 'boolean',
            'start_responder' => 'boolean',
            'large_import' => 'boolean',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['newsletter'])) $input['newsletter'] = filter_var($input['newsletter'], FILTER_SANITIZE_STRING);
        if(isset($input['send_welcome'])) $input['send_welcome'] = filter_var($input['send_welcome'], FILTER_SANITIZE_STRING);
        if(isset($input['start_responder'])) $input['start_responder'] = filter_var($input['start_responder'], FILTER_SANITIZE_STRING);
        $this->replace($input);
    }
}
