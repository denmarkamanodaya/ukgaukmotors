<?php

namespace Quantum\base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
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
            'site_notification_email' => 'nullable|email',
            'site_notification_pushbullet_api' => 'nullable|string',
            'site_notification_pushover_token' => 'nullable|string',
            'site_notification_pushover_user' => 'nullable|string',
        ];
    }
}
