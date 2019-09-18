<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : CarDataService.php
 **/

namespace App\Services;


use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleModelVarient;
use Illuminate\Support\Facades\Cache;

class CarDataService
{
    public static function allMakesStatic()
    {
        return Cache::rememberForever('vehicle_makes_full', function () {
            return VehicleMake::where('slug', '!=', 'unlisted')->orderBy('name', 'ASC')->get();
        });
    }

    public static function allMakesStaticList()
    {
        $makes = self::allMakesStatic();
        $makes = $makes->pluck('name', 'slug');
        return $makes;
    }

    public static function allModelsStatic()
    {
        $carModels = VehicleModel::with('make')->where('vehicle_make_id', '!=', '1')->orderBy('vehicle_make_id', 'ASC')->get();
        return $carModels;
    }

    public function allMakes()
    {
        return Cache::rememberForever('vehicle_makes_full', function () {
            return VehicleMake::where('slug', '!=', 'unlisted')->orderBy('name', 'ASC')->get();
        });
    }

    public function allMakesList()
    {
        $makes = $this->allMakes();
        $makes = $makes->pluck('name', 'slug');
        return $makes;
    }

    public function carMake($id)
    {
        if (Cache::tags(['Vehicle_Make_Detail'])->has('make_'.$id)) {
            return Cache::tags(['Vehicle_Make_Detail'])->get('make_'.$id);
        }
        $carMake = VehicleMake::with(['description','models' => function($query){ $query->with('vehiclesCount', 'description'); }, 'country'])->where('slug', $id)->orderBy('name', 'ASC')->firstOrFail();
        Cache::tags(['Vehicle_Make_Detail'])->forever('make_'.$id, $carMake);
        return $carMake;
    }

    public function carMakeAllModels($id)
    {
        if (Cache::tags(['Vehicle_Make_Detail'])->has('makeWithAll_'.$id)) {
            return Cache::tags(['Vehicle_Make_Detail'])->get('makeWithAll_'.$id);
        }
        $carMake = VehicleMake::with(['description','models', 'country'])->where('slug', $id)->orderBy('name', 'ASC')->firstOrFail();
        Cache::tags(['Vehicle_Make_Detail'])->forever('makeWithAll_'.$id, $carMake);
        return $carMake;
    }

    public function getModelCache($make, $model)
    {
        if (Cache::tags(['Vehicle_Model_Detail'])->has('model_'.$model)) {
            return Cache::tags(['Vehicle_Model_Detail'])->get('model_'.$model);
        }
        $carModel = VehicleModel::with('variants')->where('vehicle_make_id', $make->id)->where('slug', $model)->firstOrFail();
        Cache::tags(['Vehicle_Model_Detail'])->forever('model_'.$model, $carModel);
        return $carModel;
    }

    private function getModelVariantCache($carModel, $variant)
    {
        if (Cache::tags(['Vehicle_Model_Variant'])->has('variant_'.$variant)) {
            return Cache::tags(['Vehicle_Model_Variant'])->get('variant_'.$variant);
        }
        $carModelVariant = VehicleModelVarient::with('description')->where('vehicle_model_id', $carModel->id)->where('id', $variant)->firstOrFail();
        Cache::tags(['Vehicle_Model_Variant'])->forever('variant_'.$variant, $carModelVariant);
        return $carModelVariant;
    }

    public function getModel($make, $model)
    {
        $data['carMake'] = $this->carMake($make);
        $data['carModel'] = $this->getModelCache($data['carMake'], $model);
        return $data;
    }

    public function getVariant($make, $model, $variant)
    {
        $data['carMake'] = $this->carMake($make);
        $data['carModel'] = $this->getModelCache($data['carMake'], $model);
        $data['carVariant'] = $this->getModelVariantCache($data['carModel'], $variant);
        return $data;
    }

}