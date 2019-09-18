<?php

namespace App\Http\Controllers\Api\V1\Vehicle;

use App\Services\ImportingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleController extends Controller
{

    /**
     * @var ImportingService
     */
    private $importingService;

    public function __construct(ImportingService $importingService)
    {
        $this->importingService = $importingService;
    }

    public function getSpecifications(Request $request)
    {
        $manufacturer = $request->input('manufacturer');
        $model = $request->input('model');
        $vehicleType = $request->input('vehicleType');
        $engineSize = $request->input('engineSize');
        $type = $request->input('type');

        $makeID = $this->importingService->getMakeId($manufacturer);
        $modelID = $this->importingService->getModelId($makeID, $model);
        $typeID = $this->importingService->getTypeId($vehicleType);
        $engineSize = $this->importingService->getEngineSize($engineSize);
        $bodyType = $this->importingService->getBodyType($type);

        return response()->json([
            'makeId' => $makeID,
            'modelId' => $modelID,
            'vehicleTypeId' => $typeID,
            'engineSizeID' => ($engineSize) ? $engineSize->id : null,
            'bodyTypeID' => ($bodyType) ? $bodyType->id : null
        ]);
    }
}
