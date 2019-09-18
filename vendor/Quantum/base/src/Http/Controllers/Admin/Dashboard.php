<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\News;
use Quantum\base\Models\Page;
use Quantum\base\Models\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::Area('admin')->Published()->tenant()->latest()->get();
        $totals['users'] = User::count();
        $totals['pages'] = Page::tenant()->count();
        $totals['news'] = News::tenant()->count();
        return view('base::admin.dashboard', compact('news', 'totals'));
    }

}
