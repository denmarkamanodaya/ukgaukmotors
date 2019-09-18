<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : SeoService.php
 **/

namespace App\Services;

use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;

class SeoService
{

    public function auctioneer($auctioneer)
    {
        if(isset($auctioneer->name) && $auctioneer->name != '') {
            SEOMeta::setTitle("Check out all upcoming car auctions including " . $auctioneer->name);
            OpenGraph::setTitle("Check out all upcoming car auctions including " . $auctioneer->name);

            $vehicle_count = ( ! is_null($auctioneer->vehiclesCount) ? $auctioneer->vehiclesCount->aggregate : 0 );

            $description = "Search all car auctions at " . $auctioneer->name . " " . $auctioneer->town . " Currently " . $vehicle_count . " cars for sale";

            SEOMeta::setDescription($description);
            OpenGraph::setDescription($description);
        }

        if(config('app.name')) {
            OpenGraph::setSiteName(config('app.name'));
        }

        SEOMeta::addMeta('robots', 'index,follow', 'name');

    }

    public function vehicle($vehicle)
    {

        if(isset($vehicle->name) && $vehicle->name != '') {
            SEOMeta::setTitle('Vehicle - '.$vehicle->name);
            OpenGraph::setTitle('Vehicle - '.$vehicle->name);
        }

        if($vehicle->media->count() > 0)
        {
            if(vehicleImageExists($vehicle->media->first()->name, $vehicle->id))
            {
                $image = url('/images/vehicle/'.$vehicle->id.'/'.$vehicle->media->first()->name);
                OpenGraph::addImage($image);
            }
        }

        OpenGraph::setUrl(url('/vehicle/'.$vehicle->slug));

        if(config('app.name')) {
            OpenGraph::setSiteName(config('app.name'));
        }

            SEOMeta::addMeta('robots', 'index,follow', 'name');


    }
}
