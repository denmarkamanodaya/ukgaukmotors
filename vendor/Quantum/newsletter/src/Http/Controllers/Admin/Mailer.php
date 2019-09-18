<?php

namespace Quantum\newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use Quantum\newsletter\Http\Requests\CreateEmailShotRequest;
use Quantum\newsletter\Http\Requests\CreateNewsletterRequest;
use Quantum\newsletter\Http\Requests\CreateResponderRequest;
use Quantum\newsletter\Services\MailerService;
use Quantum\newsletter\Services\NewsletterService;
use Quantum\newsletter\Services\TemplateService;

class Mailer extends Controller
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var MailerService
     */
    private $mailerService;
    /**
     * @var TemplateService
     */
    private $templateService;

    public function __construct(MailerService $mailerService, NewsletterService $newsletterService, TemplateService $templateService)
    {
        $this->newsletterService = $newsletterService;
        $this->mailerService = $mailerService;
        $this->templateService = $templateService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timedMails = $this->mailerService->getTimedMail();
        return view('newsletter::admin/mailer/index', compact('timedMails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newsletters = $this->newsletterService->getAllNewslettersList();
        $templates = $this->templateService->getAllTemplatesList('template');
        return view('newsletter::admin/mailer/create', compact('newsletters', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmailShotRequest $request)
    {
        $this->mailerService->saveMailShot($request);
        return redirect('/admin/newsletter/mail');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timedMail = $this->mailerService->getTimedMailById($id);
        $newsletters = $this->newsletterService->getAllNewslettersList();
        return view('newsletter::admin/mailer/edit', compact('timedMail', 'newsletters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateEmailShotRequest $request, $id)
    {
        $this->mailerService->updateMailShot($request, $id);
        return redirect('/admin/newsletter/mail');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, $id)
    {
        $this->mailerService->deleteMailShot($id);
        return redirect('/admin/newsletter/mail');
    }

    public function preview($id)
    {
        $timedMail = $this->mailerService->getTimedMailById($id);
        return view('newsletter::admin/mailer/preview', compact('timedMail'));
    }

}
