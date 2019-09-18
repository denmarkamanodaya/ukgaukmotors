<?php

namespace Quantum\base\Http\Controllers\Auth;

use Auth;
use Laracasts\Flash\Flash;
use Quantum\base\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;
    //use ThrottlesLogins;

    protected $redirectPath = '/members/dashboard';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('firewall');
        
        $this->redirectPath = \Settings::get('members_home_page');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_confirmed' => false
        ]);
    }

    public function authenticated($request, $user)
    {

        if($user->email_confirmed == 'false')
        {
            Flash::error('Error : Your email needs to be verified before you can access the members area.');
            Auth::logout();
            return redirect('/confirm-email/resend');
        }
        return redirect()->intended($this->redirectPath());
    }

    protected function getCredentials(Request $request)
    {
        return [
          'email' => $request->input('email'),
          'password' => $request->input('password'),
          'status' => 'active',
        ];
    }

    protected function getLogout()
    {
        \Auth::logout();
        \Session::flush();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

}
