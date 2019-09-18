<?php

namespace Quantum\base\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Quantum\base\Rules\Recaptcha;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/members/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('firewall');
        $this->redirectTo = \Settings::get('members_home_page');
    }

    protected function validateLogin(Request $request)
    {
	    #if(\Settings::get('recaptcha_login') && \Settings::get('recaptcha_site_key') != '')
	if(true==false)
        {
            $messages = [
                'g-recaptcha-response.required' => 'Recaptcha not clicked !',
            ];
            $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
                'g-recaptcha-response' => ['required', new Recaptcha()]
            ], $messages);
        } else {
            $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }

    }

    protected function authenticated(Request $request, $user)
    {
        if($user->email_confirmed == 'false')
        {
            flash('Error : Your email needs to be verified before you can access the members area.')->error();
            Auth::logout();
            return redirect('/confirm-email/resend');
        }
        return redirect()->intended($this->redirectPath());
    }
}
