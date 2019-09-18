<?php

namespace Quantum\base\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Quantum\base\Rules\Recaptcha;

class PasswordController extends Controller
{
    protected $redirectTo = '/members/dashboard';
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = \Settings::get('members_home_page');
        $this->middleware('guest');
    }

    protected function rules()
    {
        if(\Settings::get('recaptcha_login') && \Settings::get('recaptcha_site_key') != '')
        {
            return [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6',
                'g-recaptcha-response' => ['required', new Recaptcha()]
            ];
        } else {
            return [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6',
            ];
        }

    }

    protected function validationErrorMessages()
    {
        return [
            'g-recaptcha-response.required' => 'Recaptcha not clicked !',
        ];
    }

}
