<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Settings.php
 **/

namespace Quantum\blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Quantum\blog\Http\Requests\Admin\UpdateBlogSettingsRequest;
use Quantum\blog\Services\BlogSettingsService;

class Settings extends Controller
{
    /**
     * @var BlogSettingsService
     */
    private $blogSettingsService;

    public function __construct(BlogSettingsService $blogSettingsService)
    {
        $this->blogSettingsService = $blogSettingsService;
    }

    public function index()
    {
        return view('blog::admin.Settings.index');
    }
    
    public function update(UpdateBlogSettingsRequest $request)
    {
        $this->blogSettingsService->update($request);
        return view('blog::admin.Settings.index');
    }

}