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

	    $statement = "SELECT name, type FROM dealers WHERE id = $vehicle->dealer_id";
	    $object = \DB::select($statement);
	    $dealer_object = $object[0];
		
	    $title = "Vehicle at " . ($dealer_object->type == 'auctioneer' ? 'auction' : $dealer_object->type) . " " . $vehicle->name;
	
	    SEOMeta::setTitle($title);
	    OpenGraph::setTitle($title);

	    $description = "FIND YOUR PERFECT VEHICLE | This " . $vehicle->name . " is available now at " . ($dealer_object->type == 'auctioneer' ? 'auction' : $dealer_object->type) . " from " . $dealer_object->name;

	    SEOMeta::setDescription($description);
	    OpenGraph::setDescription($description);
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

    public function motorpedia($data)
    {
	    if($data)
	    {
	            SEOMeta::setTitle($data->title);
	            OpenGraph::setTitle($data->title);

	            SEOMeta::setDescription($data->description);
        	    OpenGraph::setDescription($data->description);
	    }

    }
}
