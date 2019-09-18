<?php

namespace Quantum\base\Http\Controllers\Members;

use Quantum\base\Services\SeoService;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Services\MembershipService;
use Shortcode;

class Page extends Controller
{

    /**
     * @var SeoService
     */
    private $seoService;
    /**
     * @var MembershipService
     */
    private $membershipService;

    public function __construct(SeoService $seoService, MembershipService $membershipService)
    {
        $this->seoService = $seoService;
        $this->membershipService = $membershipService;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id='default_page')
    {
        if($id == 'default_page') $id = ltrim(\Settings::get('members_home_page'), 'members/');
        $page = \Quantum\base\Models\Page::with('roles')->where('route', $id)->Area('members')->Published()->tenant()->firstOrFail();
        foreach($page->roles as $role)
        {
            if(!\Auth::user()->hasRole($role->name)) return $this->membershipService->upgradeNeeded($role->name);
        }
        $this->seoService->page($page);
        $page->preContent = html_entity_decode($page->preContent, ENT_QUOTES);
        $page->preContent = Shortcode::parse($page->preContent);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::members.Page.show', compact('page'));
    }

}
