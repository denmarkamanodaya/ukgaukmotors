<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\HelpText;
use Quantum\base\Models\Page;
use Quantum\base\Models\Role;
use Quantum\base\Services\PageService;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Pages extends Controller
{

    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($area = false)
    {
        $area = $area ? $area : 'public';
        $validArea = array('public', 'members', 'admin');

        if (!in_array($area, $validArea, true)) abort(404);

        $pages = Page::where('area', $area)->tenant()->paginate(20);
        return view('base::admin.Page.index', compact('pages', 'area'));
    }

    public function showArea(\Quantum\base\Http\Requests\Admin\ChangeAreaRequest $changeAreaRequest)
    {
        $area = $changeAreaRequest->area;
        return $this->index($area);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        return view('base::admin.Page.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Quantum\base\Http\Requests\Admin\CreatePageRequest $request)
    {
        $page = $this->pageService->createPage($request);
        return redirect('admin/pages/'.$page->area);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::where('id', $id)->tenant()->firstOrFail();
        $revisions = $this->pageService->getPostRevisionList($page);
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        $selectedRoles = $this->pageService->roleArray($page);
        $helptext['page_content'] = HelpText::slug('page_content')->first();
        return view('base::admin.Page.edit', compact('page','roles','selectedRoles','helptext', 'revisions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\Quantum\base\Http\Requests\Admin\UpdatePageRequest $request, $id)
    {
        $this->pageService->updatePage($id, $request);
        return redirect('/admin/page/'.$id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $area = $this->pageService->deletePage($id);
        return redirect('/admin/pages/'.$area);
    }


    public function showRevision($id, $revision)
    {
        $page = Page::where('id', $id)->tenant()->firstOrFail();
        $pageRevision = $this->pageService->getRevisionById($revision, $page->id);
        $revisions = $this->pageService->getPostRevisionList($page);
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        $selectedRoles = $this->pageService->roleArray($page);
        $helptext['page_content'] = HelpText::slug('page_content')->first();
        return view('base::admin.Page.editRevision', compact('page','roles','selectedRoles','helptext', 'revisions', 'pageRevision'));
    }
}
