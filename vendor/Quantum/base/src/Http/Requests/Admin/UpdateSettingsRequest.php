<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'site_name' => 'required|string',
            'site_email_address' => 'required|email',
            'site_email_from_name' => 'required|string',
            'public_theme' => 'required|string',
            'members_theme' => 'required|string',
            'admin_theme' => 'required|string',
            'email_theme' => 'required|string',
            'members_home_page' => 'required|string',
            'contact_thankyou_page' => 'required|string',
            'members_deleted_account_page' => 'required|string',
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
            return new \JsonResponse($errors, 422);
        }

        \Flash::error('There was a problem with your input.');
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
