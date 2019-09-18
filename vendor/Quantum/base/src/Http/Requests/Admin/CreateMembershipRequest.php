<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class CreateMembershipRequest extends FormRequest
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
        $this->sanitize();
        return [
            'title' => 'string',
            'description' => 'string',
            'summary' => 'required|string',
            'roles' => 'array',
            'roles_to_remove' => 'array',
            'type' => 'required|in:free,paid',
            'amount' => 'required_if:type,paid|nullable|regex:/\b\d{1,3}(?:,?\d{3})*(?:\.\d{2})?\b/',
            'subscription' => 'boolean',
            'expires' => 'boolean',
            'subscription_period_amount' => 'nullable|required_if:subscription,1|nullable|string',
            'subscription_period_type' => 'required_if:subscription,1|string',
            'expired_remove_roles' => 'boolean',
            'expired_change_status' => 'boolean',
            'expired_change_status_to' => 'in:active,inactive',
            'expired_add_roles' => 'array',
            'emails_id' => 'required|numeric',
            'page_after_registration' => 'string',
            'members_page_after_payment' => 'required_if:subscription,1|string',
            'guest_page_after_payment' => 'required_if:subscription,1|string',
            'allow_user_signups' => 'boolean',
            'display_in_collections' => 'boolean',
            'email_validate' => 'boolean',
            'login_after_register' => 'boolean',
            'status' => 'in:active,inactive',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['summary'] = filter_var ( trim($input['summary']), FILTER_SANITIZE_STRING);
        $this->replace($input);
    }
}
