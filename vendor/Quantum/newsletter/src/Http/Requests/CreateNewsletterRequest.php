<?php

namespace Quantum\newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewsletterRequest extends FormRequest
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
        $this->sanitize();
        return [
            'title' => 'required|string',
            'summary' => 'required|string',
            'description' => 'required|string',
            'confirm_non_member' => 'boolean',
            'visible_in_lists' => 'boolean',
            'allow_subscribers' => 'boolean',
            'newsletter_roles' => 'array',
            'newsletter_no_join' => 'array',
            'welcome_email_subject' => 'required|string',
            'welcome_email' => 'required|string',
            'confirmation_email_subject' => 'required|string',
            'confirmation_email' => 'required|string',
            'subscribed_page' => 'required|string',
            'unsubscribed_page' => 'required|string',
            'email_from' => 'required|email',
            'email_from_name' => 'required|string',
            'multisite_sites.*' => 'nullable|string',
            'newsletter_templates_id' => 'required|integer',
            'shot_template_id' => 'required|integer'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['title'])) $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING);
        if(isset($input['summary'])) $input['summary'] = filter_var($input['summary'], FILTER_SANITIZE_STRING);
        if(isset($input['confirmation_email_subject'])) $input['confirmation_email_subject'] = filter_var($input['confirmation_email_subject'], FILTER_SANITIZE_STRING);
        if(isset($input['welcome_email_subject'])) $input['welcome_email_subject'] = filter_var($input['welcome_email_subject'], FILTER_SANITIZE_STRING);
        if(isset($input['email_from'])) $input['email_from'] = filter_var($input['email_from'], FILTER_SANITIZE_EMAIL);
        if(isset($input['email_from_name'])) $input['email_from_name'] = filter_var($input['email_from_name'], FILTER_SANITIZE_STRING);
        if(isset($input['multisite_sites'])) {
            foreach ($input['multisite_sites'] as $key => $value)
            {
                $input['multisite_sites'][$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        }
        $this->replace($input);
    }
}
