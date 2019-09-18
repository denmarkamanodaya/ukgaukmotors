<?php

namespace Quantum\base\Http\Controllers\Frontend;

use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Quantum\base\Http\Requests\Frontend\EmailVerifyRequest;
use Quantum\base\Models\User;
use Quantum\base\Services\EmailService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Quantum\base\Services\FirewallService;
use Shortcode;

class Verify extends Controller
{

    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var FirewallService
     */
    private $fw;

    public function __construct(EmailService $emailService, FirewallService $fw)
    {
        $this->middleware('firewall');
        $this->emailService = $emailService;
        $this->fw = $fw;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function email($id)
    {
        $user = User::where('email_code', $id)->first();

        if(!$user) {
            $this->fw->init();
            $this->fw->failure('Invalid Email Verify Code');
            abort(404);
        }

        $user->email_code = null;
        $user->email_confirmed = true;
        $user->save();

        Flash::success('Email has been verified.');
        \Activitylogger::log('Email Verified : '.$user->email, $user, $user);
        return redirect('/login');
    }

    public function resend()
    {
        return view('base::auth.resend');
    }

    public function sent(EmailVerifyRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            $this->fw->init();
            $this->fw->failure('Invalid verify Email address entered');
        } else {
            $this->emailService->send_system_mail($user, 'Email Validation');
        }

        $message = 'Please check your inbox to continue';
        return view('base::auth.resend', compact('message'));
    }


    public function message(\Quantum\base\Http\Requests\Frontend\ContactRequest $request)
    {
        $this->emailService->send_contact($request);
        $returnPage = \Settings::get('contact_thankyou_page');
        return Redirect::to($returnPage);
    }


}
