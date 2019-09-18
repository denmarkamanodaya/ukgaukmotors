<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class FileManagerUpload extends Request
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
        $rules = [];
        $nbr = count($this->input('upload')) - 1;
        foreach(range(0, $nbr) as $index) {
            $rules['upload.' . $index] = 'image|max:4000';
        }

        return $rules;
    }
}
