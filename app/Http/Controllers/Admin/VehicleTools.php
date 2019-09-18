<?php

namespace App\Http\Controllers\Admin;

use App\Services\CarDataService;
use App\Services\VehicleFeaturesService;
use App\Services\VehicleService;
use App\Services\VehicleToolsService;
use App\Services\VehicleTypeService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class VehicleTools extends Controller
{


    /**
     * @var VehicleToolsService
     */
    private $vehicleToolsService;

    public function __construct(VehicleToolsService $vehicleToolsService)
    {

        $this->vehicleToolsService = $vehicleToolsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unclassified()
    {
        $vehicles = $this->vehicleToolsService->getUnclassifiedVehicles();
        return view('admin.VehicleTools.unclassified', compact('vehicles'));
    }

    public function unclassifiedMatch($slug)
    {
        $this->vehicleToolsService->matchVehicle($slug);
        return redirect('/admin/vehicleTools/unclassified');
    }

    public function matchLogs()
    {
        return view('admin.VehicleTools.matchLogs');
    }

    public function matchData()
    {
        return $this->vehicleToolsService->matchData();
    }

    public function import()
    {
        return view('admin.VehicleTools.import');
    }

    public function importVehicles()
    {
        Artisan::call('gauk:import');
        flash('Import has been run')->success();
        return view('admin.VehicleTools.import');
    }

    public function importImages()
    {
        Artisan::call('gauk:importMedia');
        flash('Image Import has been run')->success();
        return view('admin.VehicleTools.import');
    }

    public function expire()
    {
        Artisan::call('gauk:expireAuctions');
        flash('Auction Expire has been run')->success();
        return view('admin.VehicleTools.import');
    }

    public function getExactModel($model=null, $makeId=null)
    {
        $model =$this->vehicleToolsService->getExactModel($model, $makeId, true);
        dd($model);
    }


}
