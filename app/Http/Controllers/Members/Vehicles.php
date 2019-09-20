<?php

namespace App\Http\Controllers\Members;

use App\Filters\VehicleFilters;
use App\Services\CarDataService;
use App\Services\DealerService;
use App\Services\RestrictUserService;
use App\Services\SeoService;
use App\Services\VehicleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Vehicles extends Controller
{

    /**
     * @var VehicleService
     */
    private $vehicleService;
    /**
     * @var DealerService
     */
    private $dealerService;
    /**
     * @var CarDataService
     */
    private $carDataService;
    /**
     * @var SeoService
     */
    private $seoService;

    private $restrictUserService;

    public function __construct(VehicleService $vehicleService, DealerService $dealerService, CarDataService $carDataService, RestrictUserService $restrictUserService, SeoService $seoService)
    {
        $this->vehicleService = $vehicleService;
        $this->dealerService = $dealerService;
        $this->carDataService = $carDataService;
	$this->seoService = $seoService;
	$this->restrictUserService = $restrictUserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param VehicleFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(VehicleFilters $filters)
    {
        //limit car view
        if($this->restrictUserService->restrictViewMembers('searches')) return redirect('/members/upgrade');

	if(count($filters->request->all()) > 0)
	{
		$this->restructUserService->updateCountMembers('searches');
	}
	//end limit

        $filters = $this->sanitize($filters);
        if($filters->request->auctionDay != 0)
        {
            $validator = Validator::make($filters->request->all(), [
                'search' => 'nullable|AlphaSpaces|between:3,30',
                'auctioneer' => 'alpha-dash',
                'location' => 'AlphaSpaces',
                'vehicleMake' => 'alpha-dash',
                'vehicleModel' => 'alpha-dash',
                'auctionDay' => 'date_format:Y-m-d',
                'listingType' => 'string'
            ]);
        } else {
            $validator = Validator::make($filters->request->all(), [
                'search' => 'nullable|AlphaSpaces|between:3,30',
                'auctioneer' => 'alpha-dash',
                'location' => 'AlphaSpaces',
                'vehicleMake' => 'alpha-dash',
                'vehicleModel' => 'alpha-dash',
                'auctionDay' => 'date_format:Y-m-d',
                'listingType' => 'string'
            ]);
        }


        if ($validator->fails()) {
            return redirect('members/vehicles')
                ->withErrors($validator);
        }

        $vehicles = $this->vehicleService->getCachedVehiclesPaginate($filters);

        $search = $this->vehicleService->buildSearchFilters($filters);
        $search = $this->filterPrepare($search);
        $dealerList[0] = 'Company';
        $dealerList = array_merge($dealerList,$this->dealerService->dealerSelectList()->toArray());
        $dealerLocation= [];
        $dealerLocation = array_merge($dealerLocation,$this->dealerService->getDealerCounty());
        $dealerLocation = array_merge($dealerLocation,$this->vehicleService->vehicleLocationList());
        asort($dealerLocation);
        $dealerLocation = array('0' => 'Location') + $dealerLocation;
        $vehicleMakes[0] = 'Make';
        $vehicleMakes = array_merge($vehicleMakes,$this->vehicleService->vehiclesMakeListCount());
        $auctionDays = $this->vehicleService->getAuctionDays();
        $vehicleModels[0] = 'Model';

        if(isset($filters->request->vehicleMake))
        {
            $carMake = $this->carDataService->carMake($filters->request->vehicleMake);

            foreach ($carMake->models as $model)
            {
                if(isset($model->vehiclesCount) && $model->vehiclesCount->aggregate > 0) {
                    $vehicleModels[$model->id] = $model->name;
                }

            }
        }

        $shortlist = $this->vehicleService->userShortlist();
        $viewType = 'grid';
        return view('members.Vehicles.index', compact('vehicles', 'viewType', 'search', 'dealerList', 'dealerLocation', 'vehicleMakes', 'vehicleModels', 'shortlist', 'auctionDays', 'filters'));
    }

    public function sanitize($filters)
    {

        $input = $filters->request->all();

        if(isset($input['search'])) $input['search'] = filter_var($input['search'], FILTER_SANITIZE_STRING);
        if(isset($input['auctioneer'])) $input['auctioneer'] = filter_var($input['auctioneer'], FILTER_SANITIZE_STRING);
        if(isset($input['location'])) $input['location'] = filter_var($input['location'], FILTER_SANITIZE_STRING);
        if(isset($input['vehicleMake'])) $input['vehicleMake'] = filter_var($input['vehicleMake'], FILTER_SANITIZE_STRING);
        if(isset($input['vehicleModel'])) $input['vehicleModel'] = filter_var($input['vehicleModel'], FILTER_SANITIZE_STRING);
        if(isset($input['auctionDay'])) $input['auctionDay'] = filter_var($input['auctionDay'], FILTER_SANITIZE_STRING);
        if(isset($input['listingType'])) $input['listingType'] = filter_var($input['listingType'], FILTER_SANITIZE_STRING);

        $filters->request->replace($input);
        return $filters;
    }

    private function filterPrepare($search)
    {
        if(isset($search['filters']['vehicleModel']))
        {
            if(isset($search['filters']['vehicleMake']))
            {
                $car_make = str_slug($search['filters']['vehicleMake']);
                $carMake = $this->carDataService->carMake($car_make);
                foreach ($carMake->models as $model)
                {
                    if($model->id == $search['filters']['vehicleModel'])
                    {
                        $search['filters']['vehicleModel'] = $model->name;
                        break;
                    }
                }
            }
        }
        return $search;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //limit car view
        if($this->restrictUserService->restrictViewMembers('displayed')) return redirect('/members/upgrade');
        $this->restrictUserService->updateCountMembers('displayed');
        //end limit


        \Auth::user()->load('garageFeed');
        $previous = app('Illuminate\Routing\UrlGenerator')->previous();
        $previous = str_replace(config('app.url'),'', $previous);

        $vehicle = $this->vehicleService->show($id);
        if($vehicle->dealer_id)
        {
            if($vehicle->vehicle_listing_type == 1)
            {
                $dealer = $this->dealerService->getDealerById($vehicle->dealer_id);
            } else {
                $dealer = $this->dealerService->getDealerById($vehicle->dealer_id, true);
            }
            $latestVehicles = $this->dealerService->latestVehicles($dealer);
            $dealerVehicleImages = $this->dealerService->dealerCarImages($dealer);
            if($dealerVehicleImages->count() > 10) $dealerVehicleImages = $dealerVehicleImages->random(10);
        } else {
            $dealerVehicleImages = $this->vehicleService->latestAdditions('2');
        }


        $relatedVehicles = $this->vehicleService->relatedModels($vehicle);
        $shortlist = $this->vehicleService->userShortlist();
        $this->seoService->vehicle($vehicle);
        if($vehicle->vehicle_listing_type == 1)
        {
            return view('members.Vehicles.show', compact('vehicle', 'dealer', 'latestVehicles', 'relatedVehicles', 'dealerVehicleImages', 'shortlist', 'previous'));
        }
        if($vehicle->vehicle_listing_type == 2)
        {

            $this->dealerService->addDealerMap($vehicle->location, false, true);
            $this->dealerService->addDealerMap($vehicle->location, true, true);

            if($vehicle->features)
            {
                $features = $vehicle->features->sortBy('feature.position');
                $features = $features->groupBy('feature.name');
            }
            if($vehicle->mot != '') $vehicle->mot = Carbon::createFromFormat('Y-m-d', $vehicle->mot)->toFormattedDateString();
            return view('members.Vehicles.showClassified', compact('vehicle', 'latestVehicles', 'relatedVehicles', 'shortlist', 'previous', 'features', 'dealerVehicleImages'));
        }
    }

    /**
     * Filter the vehicles
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Requests\Members\VehicleSearchRequest $request)
    {
        $params = [];
        if($request->has('auctioneer'))
        {
            if($request->auctioneer != '0') $params['auctioneer'] = $request->auctioneer;
        }
        if($request->has('location'))
        {
            if($request->location != '0') $params['location'] = $request->location;
        }

        if($request->has('vehicleMake'))
        {
            if($request->vehicleMake != '0') $params['vehicleMake'] = $request->vehicleMake;
        }

        if($request->has('vehicleModel'))
        {
            if($request->vehicleModel != '0') $params['vehicleModel'] = $request->vehicleModel;
        }

        if($request->has('search'))
        {
            if($request->search != '0') $params['search'] = filter_var($request->search, FILTER_SANITIZE_STRING);
        }

        if($request->has('auctionDay'))
        {
            if($request->auctionDay != '0') $params['auctionDay'] = filter_var($request->auctionDay, FILTER_SANITIZE_STRING);
        }

        if($request->has('listingType'))
        {
            if(is_array($request->listingType) && count($request->listingType) > 0)
            {
                $listing = '';
                foreach ($request->listingType as $lType)
                {
                    if($lType == 'auctions') $listing .= ',auctions';
                    if($lType == 'classifieds') $listing .= ',classifieds';
                    if($lType == 'trade') $listing .= ',trade';
                }
                $listing = ltrim($listing, ',');
                $params['listingType'] = $listing;
            }

        }
        return \Redirect::route('members_vehicles', $params);
    }

    public function shortlist(Request $request, $id)
    {
        if(!\Auth::user()->hasRole(\Settings::get('main_content_role'))) abort(404);
        $message = $this->vehicleService->userShortlistToggle($id);

        if($request->ajax()) return $message;
        \Flash::success('Success : '.$message['message']);
        return back();
    }
    
    public function shortlistShow()
    {
        if(!\Auth::user()->hasRole(\Settings::get('main_content_role'))) abort(404);
        $vehicles = $this->vehicleService->getShortlistedVehicles();
        $shortlist = $this->vehicleService->userShortlist();
        $viewType = 'grid';
        return view('members.Vehicles.shortlist', compact('vehicles', 'viewType', 'shortlist'));
    }

}
