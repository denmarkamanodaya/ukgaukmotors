<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Laracasts\Flash\Flash;

class CreateMenuRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'role_id' => 'required|exists:roles,id',
            'parent_start' => '',
            'parent_end' => '',
            'child_start' => '',
            'child_end' => ''
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
