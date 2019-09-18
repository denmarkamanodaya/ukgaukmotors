<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : AuctionCache.php
 **/

namespace App\Services;


use Illuminate\Support\Facades\Cache;

class AuctionCache
{

    public function cacheClear()
    {
        Cache::forget('auctioneers');
        Cache::forget('vehicleMakes');
        Cache::forget('vehicleModels');
        Cache::forget('dealers_auctioneer');
        Cache::forget('dealer_list_auctioneer');
        Cache::forget('dealer_list_classified');
        Cache::forget('vehicle_classified_county');
        Cache::tags('public_dealers_auctioneer')->flush();
        Cache::tags('members_dealers_auctioneer')->flush();
        Cache::forget('dealer_auctioneer_county');
        Cache::forget('vehicle_make_list');
        Cache::forget('vehicle_make_list_counted');
        Cache::forget('vehicle_count');
        Cache::forget('vehicle_make_with_Count');
        Cache::forget('auctionDays');
        Cache::tags('vehicle_list')->flush();
        Cache::tags('latest_vehicles')->flush();
        Cache::tags('Vehicle_detail')->flush();
        Cache::tags('dealer_details')->flush();
        Cache::tags('vehicle_search')->flush();
        Cache::tags('vehicle_shortlist')->flush();
        Cache::tags('dealer_vehicle_images')->flush();
        Cache::tags('auctionDays')->flush();
        Cache::tags('Vehicle_Model_Variant')->flush();
        Cache::tags('Vehicle_Model_Detail')->flush();
        Cache::tags('Vehicle_Make_Detail')->flush();
        Cache::tags('garage_feed')->flush();
        Cache::forget('vehicle_makes_full');
        \Artisan::call('gauk:vehicleCountLog');
    }

    public function vehicleMakeChanged()
    {
        Cache::tags('vehicle_list')->flush();
        Cache::forget('vehicle_make_with_Count');
    }

}