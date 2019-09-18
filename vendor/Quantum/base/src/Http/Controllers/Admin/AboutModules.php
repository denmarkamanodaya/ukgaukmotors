<?php

namespace Quantum\base\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\Modules;

class AboutModules extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Modules::get();
        return view('base::admin.About.index', compact('modules'));
    }
}
