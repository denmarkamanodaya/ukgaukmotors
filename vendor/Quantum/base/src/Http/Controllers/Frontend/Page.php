<?php

namespace Quantum\base\Http\Controllers\Frontend;

use Quantum\base\Services\PageService;
use Quantum\base\Services\SeoService;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Shortcode;

class Page extends Controller
{

    /**
     * @var SeoService
     */
    private $seoService;
    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(SeoService $seoService, PageService $pageService)
    {
        $this->seoService = $seoService;
        $this->pageService = $pageService;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = $this->pageService->pageWithCache('index', 'public');
        //$page = \App\Models\Page::with('meta')->where('route', 'index')->Area('public')->Published()->firstOrFail();
        $this->seoService->page($page);
        $page->preContent = html_entity_decode($page->preContent, ENT_QUOTES);
        $page->preContent = Shortcode::parse($page->preContent);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::frontend.Page.show', compact('page'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = $this->pageService->pageWithCache($id, 'public');
        //$page = \App\Models\Page::with('meta')->where('route', $id)->Area('public')->Published()->firstOrFail();
        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::frontend.Page.show', compact('page'));
    }

}
