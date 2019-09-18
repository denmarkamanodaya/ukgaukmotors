<?php

namespace App\Http\Controllers\Admin;

use App\Services\GaukSettingsService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\Role;

class GaukSettings extends Controller
{

    /**
     * @var GaukSettingsService
     */
    private $gaukSettingsService;

    public function __construct(GaukSettingsService $gaukSettingsService)
    {
        $this->gaukSettingsService = $gaukSettingsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderby('name')->where('name', '!=', 'super_admin')->pluck('title', 'name');
        return view('admin.GaukSettings.index', compact('roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\GaukSettingsRequest $request)
    {
        $this->gaukSettingsService->updateSettings($request);
        return redirect('admin/gauk-settings');
    }
}
