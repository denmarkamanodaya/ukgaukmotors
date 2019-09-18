<?php

namespace Quantum\blog\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class UpdatePostRequest extends FormRequest
{
    use RequestResponse;
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
        $segments = $this->segments();
        $id = $segments[2];
        return [
            'title' => 'required|string',
            'slug' => 'string|unique:posts,slug,'.$id.',slug,user_id,0',
            'content' => 'required',
            'summary' => 'required',
            'status' => 'required|in:published,unpublished',
            'area' => 'required|in:public,members,admin,private',
            'category' => 'exists:site_categories,id',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'type' => 'string',
            'robots' => 'string',
            'publishOnTime' => 'boolean',
            'publish_on' => 'string',
            'sticky' => 'boolean',
            'featured' => 'boolean'
        ];
    }
}
