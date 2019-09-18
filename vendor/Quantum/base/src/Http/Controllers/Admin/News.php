<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\HelpText;
use Quantum\base\Models\Role;
use Quantum\base\Services\NewsService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class News extends Controller
{

    /**
     * @var NewsService
     */
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($area = false)
    {
        $area = $area ? $area : 'members';
        $validArea = array('public', 'members', 'admin');

        if (!in_array($area, $validArea, true)) abort(404);

        $news = \Quantum\base\Models\News::where('type', 'news')->tenant()->where('area', $area)->latest()->paginate(20);
        return view('base::admin.News.index', compact('news', 'area'));
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
        $helptext['news_content'] = HelpText::slug('news_content')->first();
        return view('base::admin.News.create', compact('roles', 'helptext'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Quantum\base\Http\Requests\Admin\CreateNewsItemRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Quantum\base\Http\Requests\Admin\CreateNewsItemRequest $request)
    {
        $newsItem = $this->newsService->CreateNewsItem($request);
        return redirect('admin/news/'.$newsItem->area);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $newsItem = \Quantum\base\Models\News::where('id', $id)->tenant()->firstOrFail();
        $roles = Role::where('name', '!=', 'super_admin')->where('name', '!=', 'guest')->get();
        $selectedRoles = $this->newsService->roleArray($newsItem);
        $helptext['news_content'] = HelpText::slug('news_content')->first();
        return view('base::admin.News.edit', compact('newsItem','roles','selectedRoles', 'helptext'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Quantum\base\Http\Requests\Admin\UpdateNewsRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(\Quantum\base\Http\Requests\Admin\UpdateNewsRequest $request, $id)
    {
        $this->newsService->updateNewsItem($id, $request);
        return redirect('/admin/newsItem/'.$id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = $this->newsService->deleteNewsItem($id);
        return redirect('/admin/news/'.$area);
    }
}
