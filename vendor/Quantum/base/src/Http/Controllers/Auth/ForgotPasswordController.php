<?php

namespace Quantum\base\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Quantum\base\Rules\Recaptcha;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validateEmail(Request $request)
    {
        $messages = [
            'g-recaptcha-response.required' => 'Recaptcha not clicked !',
        ];
        if(\Settings::get('recaptcha_login') && \Settings::get('recaptcha_site_key') != '')
        {
            $this->validate($request, [
                'email' => 'required|email',
                'g-recaptcha-response' => ['required', new Recaptcha()]
            ], $messages);
        } else {
            $this->validate($request, ['email' => 'required|email']);
        }

    }
}
