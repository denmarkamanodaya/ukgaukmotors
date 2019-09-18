<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateBookRequest extends Request
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
            'front_cover' => 'nullable|string',
            'back_cover' => 'nullable|string',
            'content' => 'nullable|string',
            'details' => 'nullable|string',
            'keywords' => 'nullable|string',
            'type' => 'string',
            'robots' => 'string',
            'showBreadcrumbs' => 'nullable|boolean',
            'hideMenu' => 'nullable|boolean'
        ];
    }
}
