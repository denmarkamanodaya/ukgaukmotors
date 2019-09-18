<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : AddUserMembershipRequest.php
 **/

namespace Quantum\base\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Rules\Recaptcha;
use Quantum\base\Traits\RequestResponse;

class RegisterRequest extends FormRequest {

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
        if(\Settings::get('recaptcha_register') && \Settings::get('recaptcha_site_key') != '')
        {
            return [
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'username' => 'required|alpha_dash|max:150|unique:users,username',
                'first_name' => 'nullable|alpha',
                'last_name' => 'nullable|alpha',
                'address' => 'nullable|string',
                'address2' => 'nullable|string',
                'city' => 'nullable|string',
                'county' => 'nullable|string',
                'postcode' => 'nullable|AlphaSpaces',
                'country' => 'nullable|string',
                'telephone' => 'nullable|Telephone',
                'g-recaptcha-response' => ['required', new Recaptcha()]
            ];
        } else {
            return [
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'username' => 'required|alpha_dash|max:150|unique:users,username',
                'first_name' => 'nullable|alpha',
                'last_name' => 'nullable|alpha',
                'address' => 'nullable|string',
                'address2' => 'nullable|string',
                'city' => 'nullable|string',
                'county' => 'nullable|string',
                'postcode' => 'nullable|AlphaSpaces',
                'country' => 'nullable|string',
                'telephone' => 'nullable|Telephone',
            ];
        }

    }

    public function messages()
    {
        return [
        ];
    }

}