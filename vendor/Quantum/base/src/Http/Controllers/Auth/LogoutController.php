<?php

namespace Quantum\base\Http\Controllers\Auth;


use App\Http\Controllers\Controller;


class LogoutController extends Controller
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
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }

    protected function Logout()
    {
        \Auth::logout();
        \Session::flush();
        return redirect(url('/'));
    }

}
