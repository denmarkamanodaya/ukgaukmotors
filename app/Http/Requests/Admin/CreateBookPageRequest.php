<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateBookPageRequest extends Request
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
            'title' => 'required|string',
            'featured_image' => 'nullable|string',
            'content' => '',
            'public_view' => 'boolean'
        ];
    }
}
