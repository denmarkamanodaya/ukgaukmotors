<?php

namespace App\Http\Controllers\Admin;

use App\Services\VehicleTypeService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Converter extends Controller
{

    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;

    public function __construct(VehicleTypeService $vehicleTypeService)
    {
        $this->vehicleTypeService = $vehicleTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.Converter.index');
    }

    public function modeltomake(Requests\Admin\ConvertCarModelRequest $request)
    {
        $this->vehicleTypeService->modeltomakeConvert($request);
        return view('admin.Converter.index');
    }
}
