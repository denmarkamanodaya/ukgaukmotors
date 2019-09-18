<?php

namespace App\Http\Controllers\Admin;

use App\Models\VehicleBodyType;
use App\Models\VehicleEngineSize;
use App\Models\VehicleModel;
use App\Services\CarDataService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Ajax extends Controller
{

    /**
     * @var CarDataService
     */
    private $carDataService;

    public function __construct(CarDataService $carDataService)
    {
        $this->carDataService = $carDataService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vehicleModels( Requests\Members\AjaxVehicleModelSearch $request, $returnVal = null)
    {
        $modelCount = 0;
        if($request->vehicleMake == '0')
        {
            $output['drop'] = '<option value="0" selected="selected">Select Make First</option>';
            echo json_encode($output);
            exit;
        }
        $carMake = $this->carDataService->carMakeAllModels($request->vehicleMake);

        $output['drop'] = '<option value="0" selected="selected">Select Model</option>';
        foreach ($carMake->models as $model)
        {
                    $output['drop'] .= '<option value="'.$model->id.'">'.$model->name.'</option>';
                    $modelCount ++;

        }
        $output['drop'] .= '<option value="unlisted">Unlisted</option>';
        if($modelCount == 0) $output['drop'] = '<option value="unlisted" selected="selected">Unlisted</option>';
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
        $output['drop'] = '<option value="0" selected="selected">Select Model</option>';
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

    public function vehicleType(Requests\Members\AjaxVehicleType $request)
    {
        $data = [];
        $data['engineSize'] = '';
        $data['bodyType'] = '';
        if($request->vehicleType == 'car')
        {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
        }
        elseif($request->vehicleType == 'commercial')
        {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 3)->orderBy('position', 'ASC')->get();
        }
        elseif($request->vehicleType == 'motorbike')
        {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 4)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 4)->orderBy('position', 'ASC')->get();
        }
        else {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
        }

        foreach ($engineSize as $eSize)
        {
            $data['engineSize'] .= '<option value="'.$eSize->id.'">'.$eSize->size.'</option>';
        }
        foreach ($bodyType as $bType)
        {
            $data['bodyType'] .= '<option value="'.$bType->id.'">'.$bType->name.'</option>';
        }

        echo json_encode($data);
        exit;
    }

    public function vehicleModelVarient(Requests\Members\AjaxVehicleModelsRequest $request)
    {
        $modelCount = 0;
        if($request->vehicleModel == '0' || $request->vehicleModel == 'unlisted')
        {
            $output['drop'] = '<option value="unlisted">Unlisted</option>';
            echo json_encode($output);
            exit;
        }
        $vehicleModel = VehicleModel::with('variants')->where('id', $request->vehicleModel)->firstOrFail();

        $output['drop'] = '<option value="0" selected="selected">Select Variant</option>';
        if($vehicleModel->variants && $vehicleModel->variants->count() > 0)
        {
            foreach ($vehicleModel->variants as $variant)
            {
                $output['drop'] .= '<option value="'.$variant->id.'">'.$variant->model_desc.'</option>';
                $modelCount ++;
            }
        }

        $output['drop'] .= '<option value="unlisted">Unlisted</option>';
        if($modelCount == 0) $output['drop'] = '<option value="unlisted" selected="selected">Unlisted</option>';
        echo json_encode($output);

        exit;
    }


}
