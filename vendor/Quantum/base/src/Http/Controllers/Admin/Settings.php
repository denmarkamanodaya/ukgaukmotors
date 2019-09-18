<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\Countries;
use Quantum\base\Services\PageService;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Settings extends Controller
{

    /**
     * @var \Quantum\base\Services\Settings
     */
    private $settings;
    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(\Quantum\base\Services\Settings $settings, PageService $pageService)
    {
        $this->settings = $settings;
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = \Quantum\base\Models\Settings::tenant()->get();
        $pages = $this->pageService->getPageList('public', false);
        $memberPages = $this->pageService->getPageList('members', false);
        $themelist = \Theme::themeList();
        $countrylist = Countries::pluck('name', 'iso_3166_3');
        $sitecountry = \Countries::siteCountry();
        return view('base::admin.Settings.index', compact('settings', 'pages', 'themelist', 'memberPages', 'countrylist', 'sitecountry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\Quantum\base\Http\Requests\Admin\UpdateSettingsRequest $updateSettingsRequest)
    {
        $this->settings->updateSettings($updateSettingsRequest);
        return redirect('admin/settings');
    }
}
