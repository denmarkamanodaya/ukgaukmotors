<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : VehicleToolsService.php
 **/

namespace App\Services;


use App\Models\VehicleMatchResults;
use App\Models\Vehicles;
use Yajra\DataTables\Facades\DataTables;

class VehicleToolsService
{
    protected $vehicleMakes;

    protected $doFlash = true;

    protected $clearCache = true;

    protected $make;

    protected $model;
    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;
    /**
     * @var AuctionCache
     */
    private $auctionCache;

    public function __construct(VehicleTypeService $vehicleTypeService, AuctionCache $auctionCache)
    {
        $this->vehicleTypeService = $vehicleTypeService;
        $this->vehicleMakes = $this->vehicleTypeService->getCachedMakesFull();
        $this->auctionCache = $auctionCache;
    }

    public function getUnclassifiedVehicles()
    {
        $vehicles = Vehicles::where('vehicle_make_id', 1)->orderBy('id', 'ASC')->paginate(40);
        return $vehicles;
    }

    public function matchVehicle($slug)
    {
        $vehicle = Vehicles::where('slug', $slug)->where('vehicle_make_id', 1)->firstOrFail();
        if($vehicle->match_attempt == 2)
        {
            if($this->doFlash) flash('All Match attempts have failed')->error();
            return false;
        }

        if($vehicle->match_attempt == 1)
        {
            //model search only
            if($this->doModelsSearch($vehicle)) {
                if($this->clearCache) $this->auctionCache->vehicleMakeChanged();
                $this->logMatch($vehicle);
                return true;
            }
            if($this->doFlash) flash('Not Found as a Model Only search')->error();
            return false;
        }


        if($this->doMakeModelMatch($vehicle)) {
            if($this->clearCache) $this->auctionCache->vehicleMakeChanged();
            $this->logMatch($vehicle);
            return true;
        }
        if($this->doFlash) flash('No Make Found')->error();
        return false;
    }

    public function getExactMake($make, $fuzzySearch=false)
    {
        //dd($make);
        foreach ($this->vehicleMakes as $vehicleMake)
        {
            $makeName = $vehicleMake->name;
            if($makeName == 'Opel / Vauxhall') $makeName = ['Vauxhall', 'Opel'];
            if(is_array($makeName))
            {
                foreach ($makeName as $search)
                {
                    if(strtolower(trim($make)) == strtolower($search)) return $vehicleMake;

                }
            } else {
                if(strtolower(trim($make)) == strtolower($makeName)) return $vehicleMake;
            }

        }
        //no exact
        if($fuzzySearch)
        {
            foreach ($this->vehicleMakes as $vehicleMake)
            {
                //split name into individual words then search each word
                $nameArr = explode(' ', $make);
                foreach($nameArr as $namePart)
                {
                    $matched = $this->containsWord($vehicleMake->name, $namePart);
                    if($matched !== false) return $vehicleMake;
                }
            }
        }
        return false;
    }

    public function getExactModel($model, $makeID, $fuzzySearch=false)
    {
        //dd($model);
        $make = $this->vehicleMakes->where('id', $makeID)->first();
        if(!$make) return false;
        if($make->models && $make->models->count() == 0) return false;
        foreach ($make->models as $makeModel)
        {
            if($model == strtolower($makeModel->name)) return $makeModel;
        }
        //no exact
        if($fuzzySearch)
        {
            foreach ($make->models as $makeModel)
            {
                //split name into individual words then search each word
                $nameArr = explode(' ', $model);
                foreach($nameArr as $namePart)
                {
                    //dd($namePart, $makeModel->name);
                    //echo "$makeModel->name".PHP_EOL;
                    $matched = $this->containsWord($makeModel->name, $namePart);
                    if($matched !== false) return $makeModel;
                }
            }
        }
        return false;
    }

    private function matchReset()
    {
        $this->make = null;
        $this->model = null;
    }

    private function containsWord($str, $word)
    {
        return !!preg_match('#\\b' . preg_quote($word, '#') . '\\b#i', $str);
    }

    private function doMakeModelMatch(Vehicles $vehicle)
    {

        foreach ($this->vehicleMakes as $vehicleMake)
        {
            $this->matchReset();

            $makeName = $vehicleMake->name;
            if($makeName == 'Opel / Vauxhall') $makeName = ['Vauxhall', 'Opel'];
            if(is_array($makeName))
            {
                foreach ($makeName as $search)
                {
                    $matched = $this->containsWord($vehicle->name, $search);
                    if($matched !== false) break;
                }
            } else {
                $matched = $this->containsWord($vehicle->name, $makeName);
            }
            if($matched !== false)
            {
                $this->make = $vehicleMake;
                $vehicle->vehicle_make_id = $vehicleMake->id;
                if($this->doFlash) flash('Make Found : '.$vehicleMake->name)->success();
                $vehicle = $this->doModelMatch($vehicle, $vehicleMake);
                $vehicle->save();
                return $vehicleMake;
            }
        }
        $vehicle->match_attempt = 1;
        $vehicle->save();
        return false;
    }

    private function doModelMatch($vehicle, $vehicleMake)
    {
        if($vehicleMake->models->count() < 1) return $vehicle;

        foreach ($vehicleMake->models as $vehicleModel)
        {
            $matched = $this->containsWord($vehicle->name, $vehicleModel->name);
            if($matched !== false)
            {
                $this->model = $vehicleModel;
                $vehicle->vehicle_model_id = $vehicleModel->id;
                if($this->doFlash) flash('Model Found : '.$vehicleModel->name)->success();
                return $vehicle;
            }
        }
        return $vehicle;
    }

    private function doModelsSearch($vehicle)
    {
        foreach ($this->vehicleMakes as $vehicleMake)
        {
            $this->matchReset();

            if($vehicleMake->models->count() < 1) continue;

            foreach ($vehicleMake->models as $vehicleModel)
            {
                $matched = $this->containsWord($vehicle->name, $vehicleModel->name);
                if($matched !== false)
                {
                    $vehicle->vehicle_make_id = $vehicleMake->id;
                    $vehicle->vehicle_model_id = $vehicleModel->id;
                    $vehicle->match_attempt = 0;
                    $vehicle->save();

                    $this->make = $vehicleMake;
                    $this->model = $vehicleModel;

                    if($this->doFlash) {
                        flash('Make Found : '.$vehicleMake->name)->success();
                        flash('Model Found : '.$vehicleModel->name)->success();
                    }
                    return $vehicle;
                }
            }

        }
        $vehicle->match_attempt = 2;
        $vehicle->save();
        return false;
    }

    private function doModelFuzzySearch($makeID, $name)
    {
        $make = $this->vehicleMakes->where('id', $makeID)->first();
        if(!$make) return false;
        if($make->models && $make->models->count() == 0) return false;

            foreach ($make->models as $makeModel)
            {
                //split name into individual words then search each word
                $nameArr = explode(' ', $name);
                foreach($nameArr as $namePart)
                {
                    $matched = $this->containsWord($makeModel->name, $namePart);
                    if($matched !== false) return $makeModel;
                }
            }

        return false;
    }

    public function matchAllVehiclesWithoutModels($limit=500)
    {
        if($vehicles = Vehicles::where('vehicle_make_id', '!=', 1)->where('vehicle_model_id', 1)->where('match_attempt', 0)->orderBy('id', 'ASC')->limit($limit)->get())
        {
            foreach ($vehicles as $vehicle)
            {
               // echo 'Working on : '.$vehicle->name.PHP_EOL;
                if($model = $this->doModelFuzzySearch($vehicle->vehicle_make_id, $vehicle->name))
                {
                    //echo 'Vehicle : '.$vehicle->id.' - Assigned Model : '.$model->name.PHP_EOL;
                    $vehicle->vehicle_model_id = $model->id;
                    $vehicle->save();
                } else {
                    $vehicle->match_attempt = 2;
                    $vehicle->save();
                }
            }
            $this->auctionCache->vehicleMakeChanged();
        }
    }

    public function matchAllVehicles($limit=500)
    {
        $this->doFlash = false;
        $this->clearCache = false;
        $updated = 0;
        if($vehicles = Vehicles::where('vehicle_make_id', 1)->where('match_attempt', '<', 2)->orderBy('id', 'ASC')->limit($limit)->get())
        {
            foreach ($vehicles as $vehicle)
            {
                if($vehicle->match_attempt == 2)
                {
                    continue;
                }

                if($vehicle->match_attempt == 1)
                {
                    //model search only
                    if($this->doModelsSearch($vehicle)) {
                        $this->logMatch($vehicle);
                        $updated ++;
                    }
                }

                if($vehicle->match_attempt == 0)
                {
                    //full search only
                    if($this->doMakeModelMatch($vehicle)) {
                        $this->logMatch($vehicle);
                        $updated ++;
                    }
                }

            }
        }

        if($updated > 0)
        {
            $this->auctionCache->vehicleMakeChanged();
            \Activitylogger::log('Cron - Vehicles Matched : '.$updated, $vehicle);
        }
    }

    private function logMatch($vehicle)
    {
        VehicleMatchResults::create([
           'vehicle_id' => $vehicle->id,
           'title' => $vehicle->name,
           'vehicle_make' => isset($this->make->name) ? $this->make->name : null,
           'vehicle_model' => isset($this->model->name) ? $this->model->name : null
        ]);
    }

    public function matchData()
    {
        $matchLogs = VehicleMatchResults::with('vehicle')->orderBy('id', 'DESC')->get();

        return Datatables::of($matchLogs)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->editColumn('vehicle_make', function ($model) {
                if($model->vehicle_make == 'null') return '';
                return $model->vehicle_make;
            })
            ->editColumn('vehicle_model', function ($model) {
                if($model->vehicle_model == 'null') return '';
                return $model->vehicle_model;
            })
            ->addColumn('action', function ($matchLog) {
                if($matchLog->vehicle && $matchLog->vehicle->vehicle_listing_type == 1){
                    return '<a href="'.url('admin/vehicle/'.$matchLog->vehicle->slug.'/auctionEdit').'" class="btn bg-primary btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit Vehicle</a>';
                }
                if($matchLog->vehicle && $matchLog->vehicle->vehicle_listing_type == 2){
                    return '<a href="'.url('admin/vehicle/'.$matchLog->vehicle->slug.'/classifiedEdit').'" class="btn bg-primary btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit Vehicle</a>';
                }
            })
            ->make(true);
    }

}