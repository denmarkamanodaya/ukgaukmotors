<?php

namespace Quantum\newsletter\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Quantum\base\Models\Role;
use Quantum\base\Services\FirewallService;
use Quantum\base\Services\SeoService;
use Quantum\newsletter\Http\Requests\ManageLoginRequest;
use Quantum\newsletter\Http\Requests\SubscriberNewsletter;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterSubscriber;
use Quantum\newsletter\Services\SubscriberService;
use Quantum\newsletter\Services\NewsletterService;
use Shortcode;
use Illuminate\Http\Request;
use Session;

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
    /**
     * @var FirewallService
     */
    private $firewallService;


    public function __construct(SubscriberService $subscriberService, NewsletterService $newsletterService, SeoService $seoService, FirewallService $firewallService)
    {
        $this->newsletterService = $newsletterService;
        $this->subscriberService = $subscriberService;
        $this->seoService = $seoService;
        $this->firewallService = $firewallService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubscriberNewsletter $request, $id)
    {
        $data = $this->subscriberService->userSubscribe($request, $id);

        if($request->ajax())
        {
            unset($data['page']);
            return response()->json($data);
        }

        if($data['success'] == false)
        {
            flash($data['errorMsg'])->error();
            return back();
        }
        $page = $data['page'];
        unset($data);
        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::frontend.Page.show', compact('page'));
    }

    public function confirm($id)
    {
        $page = $this->subscriberService->confirmEmail($id);
        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::frontend.Page.show', compact('page'));
    }

    public function unsubscribe($newsletter, $id)
    {
        $page = $this->subscriberService->unsubscribe($newsletter,$id);
        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::frontend.Page.show', compact('page'));
    }

    public function manage()
    {
        return view('newsletter::frontend.manage');
    }

    public function manageLogin(ManageLoginRequest $request)
    {
        $newsSubscriptions = NewsletterSubscriber::with('newsletter', 'user')->where('email', $request->email)->get();
        if(!$newsSubscriptions || $newsSubscriptions->count() == 0)
        {
            $this->firewallService->init();
            $this->firewallService->failure('Invalid Newsletter Email Login');
            $errors = ['Email Address not found.'];
            return back()->withErrors($errors);
        }
        $subscriber = $newsSubscriptions->first()->user;
        if($subscriber)
        {
            $subscriber->load('roles');
            $roles = $subscriber->roles->pluck('id')->toArray();
        } else {
            $role = Role::where('name', 'guest')->first();
            $roles = [$role->id];
        }

        $newsletters = Newsletter::whereHas('roles', function($query) use($roles){
            $query->whereIn('id', $roles);
        })->where('visible_in_lists', 1)->where('allow_subscribers', 1)->get();
        Session::put('sub_code', $newsSubscriptions->first()->sub_code);
        Session::put('newsSubscription', $newsSubscriptions->first());
        return view('newsletter::frontend.showSubscriptions', compact('newsletters', 'subscriber', 'newsSubscriptions', 'request'));
    }

    private function validateUser()
    {
        $sub_code = session('sub_code');

        if(!$sub_code) {
            Session::forget('sub_code');
            abort(404);
        }
        $newsSubscription = Session::get('newsSubscription');

        if(!$newsSubscription) {
            $newsSubscription = NewsletterSubscriber::where('sub_code', $sub_code)->first();
        }
        if(!$newsSubscription) {
            Session::forget('sub_code');
            abort(404);
        }
        Session::put('newsSubscription', $newsSubscription);
        return $newsSubscription;
    }

    public function manageDetails(Request $request, $id)
    {
        $subUser = $this->validateUser();
        $newsletter = $this->newsletterService->getNewsletterBySlug($id);
        $data['title'] = $newsletter->title;
        $data['content'] = $newsletter->description;
        if($request->ajax())
        {
            return response()->json($data);
        }

    }

    public function manageSubscribe(Request $request, $id)
    {
        $subUser = $this->validateUser();

        $request->request->add(['email' => $subUser->email, 'first_name' => $subUser->first_name, 'last_name' => $subUser->last_name]);
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
        return view('base::frontend.Page.show', compact('page'));
    }

    public function manageUnsubscribe(Request $request, $newsletter)
    {
        $subUser = $this->validateUser();

        $page = $this->subscriberService->unsubscribeUser($newsletter, $subUser);

        if($request->ajax())
        {
            return response()->json($page);
        }

        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::frontend.Page.show', compact('page'));
    }

    public function manageLogout()
    {
        Session::forget('sub_code');
        Session::forget('newsSubscription');
        return redirect('');
    }



}
