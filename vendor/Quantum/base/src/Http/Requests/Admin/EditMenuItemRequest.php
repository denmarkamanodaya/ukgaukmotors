<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class EditMenuItemRequest extends FormRequest
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
            'id' => 'required|exists:menu_items,id',
            'menu_id' => 'required|exists:menu,id',
            'type' => 'required|in:normal,dropdown,dropdown-header,dropdown-submenu',
            'parent_id' => 'exists:menu_items,id',
            'icon' => 'required',
            'url' => 'nullable|string',
            'title' => 'required',
            'permission' => 'required|exists:permissions,name',
            'position' => 'integer'
        ];
    }
}
