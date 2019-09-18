<?php

namespace Quantum\base\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Http\Requests\CreateShortcodeRequest;
use Quantum\base\Http\Requests\EditShortcodeRequest;
use Quantum\base\Models\Shortcodes;
use Quantum\base\Services\ShortcodeService;

class Shortcode extends Controller
{

    /**
     * @var ShortcodeService
     */
    private $shortcodeService;

    /**
     * Shortcode constructor.
     * @param ShortcodeService $shortcodeService
     */
    public function __construct(ShortcodeService $shortcodeService)
    {
        $this->shortcodeService = $shortcodeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $shortcodes = Shortcodes::where('system', 0)->orderBy('id', 'ASC')->paginate(30);
        return view('base::admin.Shortcodes.index', compact('shortcodes'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('base::admin.Shortcodes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Quantum\base\Http\Requests\Admin\CreateNewsItemRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateShortcodeRequest $request)
    {
        $shortcode = $this->shortcodeService->createShortcode($request);
        return redirect('admin/shortcodes');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shortcode = Shortcodes::where('id', $id)->where('system', 0)->firstOrFail();
        return view('base::admin.Shortcodes.edit', compact('shortcode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Quantum\base\Http\Requests\Admin\UpdateNewsRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditShortcodeRequest $request, $id)
    {
        $this->shortcodeService->updateShortcode($id, $request);
        return redirect('/admin/shortcode/'.$id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->shortcodeService->deleteShortcode($id);
        return redirect('/admin/shortcodes');
    }
}