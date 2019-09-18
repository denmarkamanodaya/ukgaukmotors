<?php

namespace Quantum\newsletter\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Quantum\base\Services\SeoService;
use Quantum\newsletter\Http\Requests\SubscriberNewsletter;
use Quantum\newsletter\Services\SubscriberService;
use Response;
use Quantum\newsletter\Services\MailerService;
use Quantum\newsletter\Services\NewsletterService;
use Shortcode;
use Illuminate\Http\Request;

class Subscribe extends Controller
{

    /**
     * @var NewsletterService
     */
    private $newsletterService;
    /**
     * @var SubscriberService
     */
    private $subscriberService;
    /**
     * @var SeoService
     */
    private $seoService;

    public function __construct(SubscriberService $subscriberService, NewsletterService $newsletterService, SeoService $seoService)
    {
        $this->newsletterService = $newsletterService;
        $this->subscriberService = $subscriberService;
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $user = \Auth::user();
        $request->request->add(['email' => $user->email, 'first_name' => $user->profile->first_name, 'last_name' => $user->profile->last_name]);
        $data = $this->subscriberService->userSubscribe($request, $id);

        if($request->ajax())
        {
            unset($data['page']);
            return response()->json($data);
        }
        if(!$data['success'])
        {
            \Flash::error($data['errorMsg']);
            return back();
        }
        $page = $data['page'];
        unset($data);
        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::members.Page.show', compact('page'));
    }

    public function unsubscribe(Request $request, $newsletter)
    {
        $page = $this->subscriberService->unsubscribeMember($newsletter);

        if($request->ajax())
        {
            return response()->json($page);
        }

        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);



        return view('base::members.Page.show', compact('page'));
    }

    public function details(Request $request, $id)
    {
        $newsletter = $this->newsletterService->getNewsletterBySlug($id);
        $data['title'] = $newsletter->title;
        $data['content'] = $newsletter->description;
        if($request->ajax())
        {
            return response()->json($data);
        }

    }


}
