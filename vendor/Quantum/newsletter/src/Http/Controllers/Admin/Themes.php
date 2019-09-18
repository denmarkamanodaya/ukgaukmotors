<?php

namespace Quantum\newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\newsletter\Http\Requests\CreateNewsletterRequest;
use Quantum\newsletter\Http\Requests\CreateResponderRequest;
use Quantum\newsletter\Http\Requests\CreateTemplateRequest;
use Quantum\newsletter\Services\NewsletterService;
use Quantum\newsletter\Services\TemplateService;

class Themes extends Controller
{


    /**
     * @var TemplateService
     */
    private $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = $this->templateService->getAllTemplates('theme');
        return view('newsletter::admin/themes/index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newsletter::admin/themes/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTemplateRequest $request)
    {
        $this->templateService->createTemplate($request);
        return redirect('/admin/newsletter/themes');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = $this->templateService->getTemplateBySlug($id, 'theme');
        return view('newsletter::admin/themes/edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTemplateRequest $request, $id)
    {
        $this->templateService->updateTemplate($request, $id);
        return redirect('/admin/newsletter/themes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $this->templateService->deleteTemplate($id, 'theme');
        return redirect('/admin/newsletter/themes');
    }
}
