<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            'subtitle' => 'nullable|string',
            'route' => 'required|alpha_dash',
            'content' => 'required',
            'status' => 'required|in:published,unpublished',
            'area' => 'required|in:public,members,admin',
            'roles' => 'array',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'type' => 'string',
            'robots' => 'string',
            'showBreadcrumbs' => 'nullable|boolean',
            'hideMenu' => 'nullable|boolean'
        ];
    }
}
