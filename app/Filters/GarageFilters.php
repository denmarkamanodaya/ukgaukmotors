<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : GarageFilters.php
 **/

namespace App\Filters;

use App\Models\Dealers;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Services\DealerService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class GarageFilters
{
    /**
     * @var DealerService
     */
    private $dealerService;
    private $builder;

    public function __construct(DealerService $dealerService)
    {
        $this->dealerService = $dealerService;
    }

    public function setBuilder($query)
    {
        $this->builder = $query;
    }

    /**
     * Filter by dealer.
     *
     * @param  string $dealerSlug
     * @return Builder
     */
    public function auctioneer($dealerSlug=null)
    {
        if($dealerSlug == null) return $this->builder;
        $dealer = $this->dealerService->getDealer($dealerSlug);
        return $this->builder->where('dealer_id', $dealer->id);
    }

    /**
     * Filter by county.
     *
     * @param null $location
     * @return Builder
     */
    public function location($location=null)
    {
        if($location == null) return $this->builder;
        return $this->builder->whereHas('dealer', function ($q) use ($location) {
            $q->where('county', $location);
        });
    }

    /**
     * Filter by make.
     *
     * @param null $vehicleMake
     * @return Builder
     */
    public function vehicleMake($vehicleMake=null)
    {
        if($vehicleMake == null) return $this->builder;
        if($make = VehicleMake::where('slug', $vehicleMake)->first())
        {
            return $this->builder->where('vehicle_make_id', $make->id);
        }
        return $this->builder;
    }

    /**
     * Filter by make.
     *
     * @param null $vehicleMake
     * @return Builder
     */
    public function vehicleModel($vehicleModel=null)
    {
        if($vehicleModel == null) return $this->builder;
        if($vehicleModelR = VehicleModel::where('id', $vehicleModel)->first())
        {
            return $this->builder->where('vehicle_model_id', $vehicleModelR->id);
        }
        return $this->builder;
    }

    /**
     * Filter by text.
     *
     * @param null $search
     * @return Builder
     */
    public function search($search=null)
    {
        if($search == null) return $this->builder;
        return $this->builder->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('description', 'LIKE', '%'.$search.'%');
        });
        //return $this->builder->where('name', 'LIKE', '%'.$search.'%')->orWhere('description', 'LIKE', '%'.$search.'%');
    }

    /**
     * Filter by date.
     *
     * @param  string $auctionDay
     * @return Builder
     */
    public function auctionDay($auctionDay=null)
    {
        if($auctionDay == null) return $this->builder;
        if($auctionDay == 0) return $this->builder;
        return $this->builder->whereDate('auction_date', '=', $auctionDay);
    }


    public function vehicle_listing_type($vehicle_listing_type=null)
    {
        if($vehicle_listing_type == null) return $this->builder;
        if($vehicle_listing_type == 0) return $this->builder;
        return $this->builder->where('vehicle_listing_type', '=', $vehicle_listing_type);
    }
}