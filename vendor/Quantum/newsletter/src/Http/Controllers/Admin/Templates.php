<?php

namespace Quantum\newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\newsletter\Http\Requests\CreateNewsletterRequest;
use Quantum\newsletter\Http\Requests\CreateResponderRequest;
use Quantum\newsletter\Http\Requests\CreateTemplateRequest;
use Quantum\newsletter\Http\Requests\GetTemplateRequest;
use Quantum\newsletter\Services\NewsletterService;
use Quantum\newsletter\Services\TemplateService;

class Templates extends Controller
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
        $templates = $this->templateService->getAllTemplates('template');
        return view('newsletter::admin/templates/index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newsletter::admin/templates/create');
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
        return redirect('/admin/newsletter/templates');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = $this->templateService->getTemplateBySlug($id, 'template');
        return view('newsletter::admin/templates/edit', compact('template'));
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
        return redirect('/admin/newsletter/templates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $this->templateService->deleteTemplate($id);
        return redirect('/admin/newsletter/templates');
    }

    public function getTemplate(GetTemplateRequest $request)
    {
        $template = $this->templateService->getTemplateById($request->newsletter_template, 'template');
        $data['success'] = true;
        $data['content'] = $template->content;

        if($request->ajax())
        {
            return response()->json($data);
        }
    }
}
