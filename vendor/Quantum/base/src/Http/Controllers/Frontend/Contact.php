<?php

namespace Quantum\base\Http\Controllers\Frontend;

use Illuminate\Support\Facades\View;
use Quantum\base\Services\EmailService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Shortcode;

class Contact extends Controller
{

    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = View::make('base::frontend.Contact.index');
        $contents = $view->render();
        $contents = Shortcode::parse($contents);
        return $contents;
    }


    public function message(\Quantum\base\Http\Requests\Frontend\ContactRequest $request)
    {
        $this->emailService->send_contact($request);
        $returnPage = \Settings::get('contact_thankyou_page');
        return Redirect::to($returnPage);
    }

    
}
