<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Services\ModulesService;
use Quantum\base\Services\UpdateService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Update extends Controller
{

    /**
     * @var UpdateService
     */
    private $updateService;
    /**
     * @var ModulesService
     */
    private $modulesService;

    public function __construct(UpdateService $updateService, ModulesService $modulesService)
    {
        $this->updateService = $updateService;
        $this->modulesService = $modulesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->modulesService->installModules();
        $this->updateService->doUpdate();
        return redirect('admin/dashboard');
    }

}
