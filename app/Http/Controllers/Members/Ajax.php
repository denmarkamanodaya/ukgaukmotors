<?php

namespace App\Http\Controllers\Members;

use App\Services\CarDataService;
use App\Services\DealerService;
use App\Services\VehicleService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Ajax extends Controller
{

    /**
     * @var CarDataService
     */
    private $carDataService;
    /**
     * @var DealerService
     */
    private $dealerService;
    /**
     * @var VehicleService
     */
    private $vehicleService;

    public function __construct(CarDataService $carDataService, DealerService $dealerService, VehicleService $vehicleService)
    {
        $this->carDataService = $carDataService;
        $this->dealerService = $dealerService;
        $this->vehicleService = $vehicleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vehicleModelSearch( Requests\Members\AjaxVehicleModelSearch $request, $returnVal = null)
    {
        $modelCount = 0;
        if($request->vehicleMake == '0')
        {
            $output['drop'] = '<option value="0" selected="selected">Select Make First</option>';
            echo json_encode($output);
            exit;
        }
        $carMake = $this->carDataService->carMake($request->vehicleMake);
        $output['drop'] = '<option value="0" selected="selected">Model</option>';
        foreach ($carMake->models as $model)
        {
            if(isset($returnVal) && $returnVal == 'slug')
            {
                $output['drop'] .= '<option value="'.$model->slug.'">'.$model->name.'</option>';
                $modelCount ++;
            } else {
                if(isset($model->vehiclesCount) && $model->vehiclesCount->aggregate > 0) {
                    $output['drop'] .= '<option value="'.$model->id.'">'.$model->name.'</option>';
                    $modelCount ++;
                }
            }
        }
        if($modelCount == 0) $output['drop'] = '<option value="0" selected="selected">No Models Found</option>';
        echo json_encode($output);

        exit;
    }

    public function vehicleModelSearchAll( Requests\Members\AjaxVehicleModelSearch $request, $returnVal = null)
    {
        $modelCount = 0;
        if($request->vehicleMake == '0')
        {
            $output['drop'] = '<option value="0" selected="selected">Select Make First</option>';
            echo json_encode($output);
            exit;
        }
        $carMake = $this->carDataService->carMakeAllModels($request->vehicleMake);
        $output['drop'] = '<option value="0" selected="selected">Model</option>';
        foreach ($carMake->models as $model)
        {
            if(isset($returnVal) && $returnVal == 'slug')
            {
                $output['drop'] .= '<option value="'.$model->slug.'">'.$model->name.'</option>';
                $modelCount ++;
            } else {
                    $output['drop'] .= '<option value="'.$model->id.'">'.$model->name.'</option>';
                    $modelCount ++;
            }
        }
        if($modelCount == 0) $output['drop'] = '<option value="0" selected="selected">No Models Found</option>';
        echo json_encode($output);

        exit;
    }

    public function vehicleLocations(Requests\Members\vehicleLocationsRequest $request)
    {
        $dealerLocation= [];
        if($request->auctions == 'false' && $request->classifieds == 'false')
        {
            $request->auctions = 'true';
            $request->classifieds = 'true';
        }
        if($request->auctions == 'true') $dealerLocation = array_merge($dealerLocation,$this->dealerService->getDealerCounty());
        if($request->classifieds == 'true') $dealerLocation = array_merge($dealerLocation,$this->vehicleService->vehicleLocationList());
        asort($dealerLocation);
        $output['drop'] = '<option value="0" selected="selected">Location</option>';
        $locationCount = 0;
        foreach ($dealerLocation as $location)
        {
                $output['drop'] .= '<option value="'.$location.'">'.$location.'</option>';
                $locationCount ++;

        }
        if($locationCount == 0) $output['drop'] = '<option value="0" selected="selected">No Locations Found</option>';
        echo json_encode($output);

        exit;
    }

    public function getDealers(Requests\Members\getDealersRequest $request)
    {
        $dealerList = $this->dealerService->dealerSelectList($request->dealerType)->toArray();

        $output['drop'] = '<option value="0" selected="selected">Company</option>';
        $dealerCount = 0;
        foreach ($dealerList as $key => $dealer)
        {
            $output['drop'] .= '<option value="'.$key.'">'.$dealer.'</option>';
            $dealerCount ++;

        }
        if($dealerCount == 0) $output['drop'] = '<option value="0" selected="selected">No Companies Found</option>';
        echo json_encode($output);
    }


}
