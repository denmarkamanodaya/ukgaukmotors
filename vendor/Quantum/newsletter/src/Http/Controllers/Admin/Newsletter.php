<?php

namespace Quantum\newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Quantum\newsletter\Http\Requests\CreateNewsletterRequest;
use Quantum\newsletter\Http\Requests\CreateResponderRequest;
use Quantum\newsletter\Services\MailerService;
use Quantum\newsletter\Services\NewsletterService;
use Quantum\newsletter\Services\TemplateService;

class Newsletter extends Controller
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var TemplateService
     */
    private $templateService;
    /**
     * @var MailerService
     */
    private $mailerService;

    public function __construct(NewsletterService $newsletterService, TemplateService $templateService, MailerService $mailerService)
    {
        $this->newsletterService = $newsletterService;
        $this->templateService = $templateService;
        $this->mailerService = $mailerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = $this->newsletterService->getAllNewsletters();
        return view('newsletter::admin/index', compact('newsletters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = \Quantum\base\Models\Role::where('name', '!=', 'super_admin')->orderBy('id')->pluck('title', 'id');
        $newsletters = $this->newsletterService->getAllNewslettersList();
        $themes = $this->templateService->getAllTemplatesList('theme');
        $templates = $this->templateService->getAllTemplatesList('template');
        return view('newsletter::admin/create', compact('roles', 'newsletters', 'templates', 'themes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNewsletterRequest $request)
    {
        $this->newsletterService->createNewsletter($request);
        return redirect('/admin/newsletter');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $curnewsletter = $this->newsletterService->getNewsletterBySlug($id);
        $roles = \Quantum\base\Models\Role::where('name', '!=', 'super_admin')->orderBy('id')->pluck('title', 'id');
        $newsletters = $this->newsletterService->getAllNewslettersList();
        $themes = $this->templateService->getAllTemplatesList('theme');
        return view('newsletter::admin/edit', compact('curnewsletter', 'roles', 'newsletters', 'themes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateNewsletterRequest $request, $id)
    {
        $this->newsletterService->updateNewsletter($request, $id);
        return redirect('/admin/newsletter');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $curnewsletter = $this->newsletterService->getNewsletterBySlug($id, ['subscriberCount']);
        //dd($curnewsletter);
        $curnewsletter->subscriberCount = isset($curnewsletter->subscriberCount->aggregate) ? $curnewsletter->subscriberCount->aggregate : 0;
        $newsletters[0] = 'Just Remove Subscribers';
        $newsletters = array_merge($newsletters,$this->newsletterService->getAllNewslettersList($curnewsletter->id)->toArray());
        return view('newsletter::admin/delete', compact('curnewsletter', 'newsletters'));
    }
    public function destroy(Request $request, $id)
    {
        $this->newsletterService->deleteNewsletter($request);
        return redirect('/admin/newsletter');
    }

    public function responders($id)
    {
        $newsletter = $this->newsletterService->getNewsletterBySlug($id);
        $newsletter->responders = $newsletter->mails->where('message_type', 'responder')->sortBy('position');
        return view('newsletter::admin/responders/index', compact('newsletter'));
    }

    public function responderCreate($id)
    {
        $newsletter = $this->newsletterService->getNewsletterBySlug($id);
        $templates = $this->templateService->getAllTemplatesList('template');
        return view('newsletter::admin/responders/create', compact('newsletter', 'templates'));

    }

    public function responderStore(CreateResponderRequest $request, $id)
    {
        $this->newsletterService->createResponder($request, $id);
        return redirect('/admin/newsletter/'.$id.'/responders');
    }

    public function responderEdit($newsletter, $id)
    {
        $newsletter = $this->newsletterService->getNewsletterBySlug($newsletter);
        $responder = $newsletter->mails->filter(function($item) use($id){
            return $item->id == $id && $item->message_type == 'responder';
        })->first();
        if(!$responder) abort(404);
        return view('newsletter::admin/responders/edit', compact('newsletter', 'responder'));
    }

    public function responderUpdate(CreateResponderRequest $request, $newsletter, $id)
    {
        $this->newsletterService->updateResponder($request, $newsletter, $id);
        return redirect('/admin/newsletter/'.$newsletter.'/responders');
    }

    public function updateResponderPositions(Request $request, $id)
    {
        $this->newsletterService->updateResponderPositions($request, $id);
        return redirect('/admin/newsletter/'.$id.'/responders');
    }

    public function responderDelete($newsletter, $id)
    {
        $this->newsletterService->responderDelete($newsletter, $id);
        return redirect('/admin/newsletter/'.$newsletter.'/responders');
    }

    public function getCode($id)
    {
        $newsletter = $this->newsletterService->getNewsletterBySlug($id);
        return view('newsletter::admin/getCode', compact('newsletter'));
    }

    public function sendShot()
    {
        $this->mailerService->sendMailShotsBatched();
    }
}
