<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class CreateNewsItemRequest extends FormRequest
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
        return [
            'title' => 'required|string',
            'content' => 'required',
            'header-content' => '',
            'status' => 'required|in:published,unpublished',
            'area' => 'required|in:public,members,admin',
            'type' => 'required|in:news,snippet',
            'roles' => 'array'
        ];
    }
}
