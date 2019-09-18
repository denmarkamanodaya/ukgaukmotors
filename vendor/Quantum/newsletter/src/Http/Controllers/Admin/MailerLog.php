<?php

namespace Quantum\newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Quantum\newsletter\Http\Requests\CreateEmailShotRequest;
use Quantum\newsletter\Http\Requests\CreateNewsletterRequest;
use Quantum\newsletter\Http\Requests\CreateResponderRequest;
use Quantum\newsletter\Services\MailerService;
use Quantum\newsletter\Services\NewsletterService;

class MailerLog extends Controller
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var MailerService
     */
    private $mailerService;

    public function __construct(MailerService $mailerService, NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
        $this->mailerService = $mailerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timedMails = $this->mailerService->getSentMail();
        return view('newsletter::admin/mailerLog/index', compact('timedMails'));
    }

    /**
     * Show the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $timedMail = $this->mailerService->getSentMailById($id);
        return view('newsletter::admin/mailerLog/show', compact('timedMail'));
    }


}
