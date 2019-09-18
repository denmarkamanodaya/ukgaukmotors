<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CacheSettings extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.CacheSettings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearAll()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        \Flash::success('Success : The cache has been cleared.');
        return view('admin.CacheSettings.index');
    }


}
