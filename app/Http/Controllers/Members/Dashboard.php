<?php

namespace App\Http\Controllers\Members;

use Quantum\base\Models\News;
use Illuminate\Http\Request;

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
        $news = News::Area('members')->Published()->latest()->get();
        return view('members.dashboard', compact('news'));
    }

}
