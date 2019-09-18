<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Laracasts\Flash\Flash;

class CreateMenuItemRequest extends FormRequest
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

    /**
     * @param array $errors
     * @return $this|JsonResponse
     */
    public function response(array $errors)
    {
        if ($this->ajax())
        {
            return new JsonResponse($errors, 422);
        }

        Flash::error('There was a problem with your input.');
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
