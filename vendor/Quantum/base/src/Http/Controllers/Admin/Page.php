<?php

namespace Quantum\base\Http\Controllers\Admin;

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
    public function show($id)
    {
        $page = $this->pageService->pageWithCache($id, 'admin');
        $this->seoService->page($page);
        $page->content = html_entity_decode($page->content, ENT_QUOTES);
        $page->content = Shortcode::parse($page->content);
        return view('base::admin.Page.show', compact('page'));
    }

}
