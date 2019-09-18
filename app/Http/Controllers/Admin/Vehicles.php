<?php

namespace App\Http\Controllers\Admin;

use App\Services\CarDataService;
use App\Services\VehicleFeaturesService;
use App\Services\VehicleService;
use App\Services\VehicleTypeService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Vehicles extends Controller
{

    /**
     * @var VehicleService
     */
    private $vehicleService;
    /**
     * @var VehicleFeaturesService
     */
    private $vehicleFeaturesService;
    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;
    /**
     * @var CarDataService
     */
    private $carDataService;

    public function __construct(VehicleService $vehicleService, VehicleFeaturesService $vehicleFeaturesService, VehicleTypeService $vehicleTypeService, CarDataService $carDataService)
    {
        $this->vehicleService = $vehicleService;
        $this->vehicleFeaturesService = $vehicleFeaturesService;
        $this->vehicleTypeService = $vehicleTypeService;
        $this->carDataService = $carDataService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = $this->vehicleService->getPaginatedList(1);
        return view('admin.Vehicles.index', compact('vehicles'));
    }

    public function indexClassified()
    {
        $vehicles = $this->vehicleService->getPaginatedList(2);
        return view('admin.Vehicles.Classifieds.index', compact('vehicles'));
    }

    public function indexTrade()
    {
        $vehicles = $this->vehicleService->getPaginatedList(3);
        return view('admin.Vehicles.index', compact('vehicles'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vehicle = $this->vehicleService->show($id);
        return view('admin.Vehicles.show', compact('vehicle'));
    }

    public function classifiedCreate()
    {
        $vehicleTypes = $this->vehicleTypeService->getCachedList();
        $vehicleMakes[0] = 'Select Make';
        $vehicleMakes = array_merge($vehicleMakes,$this->vehicleService->vehiclesMakeList());
        $vehicleModels[0] = 'Select Model';
        $vehicleVarients[0] = 'Select Varient';
        $features = $this->vehicleFeaturesService->getFeaturesListing();
        $vehicleMetaList = $this->vehicleService->getvehicleMetaList();
        return view('admin.Vehicles.Classifieds.create', compact('vehicleMakes', 'vehicleModels', 'features', 'vehicleVarients', 'vehicleTypes', 'vehicleMetaList'));
    }

    public function storeClassified(Requests\Admin\CreateClassifiedRequest $request)
    {
        $this->vehicleService->createClassified($request);
        return redirect('/admin/vehicles/classifieds');
    }

    public function auctionEdit($id)
    {
        $previous = app('Illuminate\Routing\UrlGenerator')->previous();
        $previous = str_replace(config('app.url'),'', $previous);

        $vehicle = $this->vehicleService->show($id);
        $vehicleTypes = $this->vehicleTypeService->getCachedList();
        $vehicleMakes[0] = 'Select Make';
        $vehicleMakes = array_merge($vehicleMakes,$this->vehicleService->vehiclesMakeList());
        $vehicleModels[0] = 'Select Model';

        $vehicleVarients[0] = 'Select Variant';
        $features = $this->vehicleFeaturesService->getFeaturesListing();

        if($vehicle->make)
        {
            $carMake = $this->carDataService->carMakeAllModels($vehicle->make->slug);
            $vehicleModelList = $carMake->models->pluck('name', 'id')->toArray();
            $vehicleModels = $vehicleModels + $vehicleModelList;
        }

        $vehicleModels['unlisted'] = 'Unlisted';

        if($vehicle->model && $vehicle->model->slug != 'unlisted')
        {
            $vehicleModel = $this->carDataService->getModelCache($vehicle->make, $vehicle->model->slug);
            $vehicleVarientsList = $vehicleModel->variants->pluck('model_desc', 'id')->toArray();
            $vehicleVarients = $vehicleVarients + $vehicleVarientsList;
        }

        $vehicleVarients['unlisted'] = 'Unlisted';

        $vehicleMetaList = $this->vehicleService->getvehicleMetaList($vehicle);
        return view('admin.Vehicles.edit', compact('vehicle', 'vehicleMakes', 'vehicleModels', 'features', 'vehicleVarients', 'vehicleTypes', 'vehicleMetaList', 'previous'));

    }

    public function classifiedEdit($id)
    {
        $vehicle = $this->vehicleService->show($id);
        $vehicleTypes = $this->vehicleTypeService->getCachedList();
        $vehicleMakes[0] = 'Select Make';
        $vehicleMakes = array_merge($vehicleMakes,$this->vehicleService->vehiclesMakeList());
        $vehicleModels[0] = 'Select Model';

        $vehicleVarients[0] = 'Select Variant';
        $features = $this->vehicleFeaturesService->getFeaturesListing();

        $carMake = $this->carDataService->carMakeAllModels($vehicle->make->slug);
        $vehicleModelList = $carMake->models->pluck('name', 'id')->toArray();
        $vehicleModels = $vehicleModels + $vehicleModelList;
        $vehicleModels['unlisted'] = 'Unlisted';

        if($vehicle->model && $vehicle->model->slug != 'unlisted')
        {
            $vehicleModel = $this->carDataService->getModelCache($vehicle->make, $vehicle->model->slug);
            $vehicleVarientsList = $vehicleModel->variants->pluck('model_desc', 'id')->toArray();
            $vehicleVarients = $vehicleVarients + $vehicleVarientsList;
        }

        $vehicleVarients['unlisted'] = 'Unlisted';

        $vehicleMetaList = $this->vehicleService->getvehicleMetaList($vehicle);
        return view('admin.Vehicles.Classifieds.edit', compact('vehicle', 'vehicleMakes', 'vehicleModels', 'features', 'vehicleVarients', 'vehicleTypes', 'vehicleMetaList'));

    }

    public function classifiedUpdate(Requests\Admin\CreateClassifiedRequest $request, $id)
    {
        $this->vehicleService->updateClassified($request, $id);
        return redirect('/admin/vehicle/'.$id.'/classifiedEdit');
    }

    public function auctionUpdate(Requests\Admin\CreateAuctionRequest $request, $id)
    {
        $this->vehicleService->updateAuction($request, $id);
        return redirect('/admin/vehicle/'.$id.'/auctionEdit');
    }

    public function loadImages($id)
    {
        $vehicle = $this->vehicleService->show($id);
        return view('admin.Vehicles.Classifieds.partials.images', compact('vehicle'));

    }

    public function uploadImages(Request $request, $id)
    {
        $upload = $this->vehicleService->uploadImages($request, $id);
        if( $upload ) {
            return \Response::json('success', 200);
        } else {
            return \Response::json('error', 400);
        }
    }
    public function deleteImage($id, $image)
    {
        $this->vehicleService->deleteImage($id, $image);
        return redirect('/admin/vehicle/'.$id.'/classifiedEdit');
    }

    public function defaultImage($id, $image)
    {
        $this->vehicleService->setDefaultImage($id, $image);
        return redirect('/admin/vehicle/'.$id.'/classifiedEdit');
    }


}
