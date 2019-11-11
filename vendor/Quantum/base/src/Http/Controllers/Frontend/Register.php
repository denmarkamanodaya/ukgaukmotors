<?php

namespace Quantum\base\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Quantum\base\Services\SeoService;
use Quantum\base\Http\Requests\Frontend\RegisterRequest;
use Quantum\base\Services\MembershipService;
use Quantum\base\Services\PageService;
use Shortcode;

class Register extends Controller
{

    /**
     * @var PageService
     */
    private $pageService;
    /**
     * @var SeoService
     */
    private $seoService;
    /**
     * @var MembershipService
     */
    private $membershipService;


    /**
     * Create a new register controller instance.
     *
     * @return void
     */
    public function __construct(MembershipService $membershipService, PageService $pageService, SeoService $seoService)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('firewall');
        $this->pageService = $pageService;
        $this->seoService = $seoService;
        $this->membershipService = $membershipService;
    }
    
    public function index($membership = null)
    {
        if($this->membershipService->showRegisterForm($membership))
        {
            $page = $this->pageService->pageWithCache('register', 'public');
            $this->seoService->page($page);
            $page->content = html_entity_decode($page->content, ENT_QUOTES);
            $page->content = Shortcode::parse($page->content);
            return view('base::frontend.Page.show', compact('page'));
        } else {
            abort(404);
        }
    }
    
    public function store(RegisterRequest $request)
    {
        $membership = $this->membershipService->postedRegisterForm($request);
        #return redirect($membership->page_after_registration);
	return redirect('/social-media-share');
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Recaptcha not clicked !'
        ];
    }
    
}
