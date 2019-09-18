<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\HelpText;
use Quantum\base\Models\Role;
use Quantum\base\Services\IconService;
use Quantum\base\Services\NewsService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Icon extends Controller
{

    /**
     * @var NewsService
     */
    private $newsService;
    /**
     * @var IconService
     */
    private $iconService;

    public function __construct(IconService $iconService)
    {
        $this->iconService = $iconService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exists = \Storage::exists('public\sortedIcons.json');
        if(!$exists) $this->iconService->makeJson();
        $fajson = \Storage::get('public\sortedIcons.json');
        $faicons = json_decode($fajson, true);
        //dd($faicons);
        return view('base::admin.Icons.index', compact('faicons'));
    }

    public function makeJson()
    {
        $this->iconService->makeJson();
    }
}
