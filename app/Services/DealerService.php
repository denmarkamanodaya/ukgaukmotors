<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : DealerService.php
 **/

namespace App\Services;


use App\Models\Dealers;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Cache;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\Input;

class DealerService
{
    public function __construct()
    {
        \Config::set('googlmapper.GOOGLE_API_KEY', \Settings::get('google_map_api_key'));
    }

    public function getDealers($addmap = false, $type = 'auctioneer')
    {
        $dealers = $this->getCachedDealersPaginate($type);
        if($addmap) $this->addMap($dealers);
        return $dealers;
    }
    
    public function latestVehicles($dealer)
    {
        if (Cache::tags(['latest_vehicles'])->has('dealer_'.$dealer->id)) {
            return Cache::tags(['latest_vehicles'])->get('dealer_'.$dealer->id);
        }
         $latestVehicles = Vehicles::with('media')->where('dealer_id', $dealer->id)->orderBy('created_at', 'DESC')->limit(9)->get();
        Cache::tags(['latest_vehicles'])->forever('dealer_'.$dealer->id, $latestVehicles);
        return $latestVehicles;
    }

    public function auctionDays($dealer)
    {
        if (Cache::tags(['auctionDays'])->has('dealer_'.$dealer->id)) {
            return Cache::tags(['auctionDays'])->get('dealer_'.$dealer->id);
        }
            $auctionDays = Vehicles::select(array(
                \DB::raw('DATE(`auction_date`) as `date`'),
                \DB::raw('COUNT(*)as `count`')
            ))
                ->where('dealer_id', $dealer->id)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->pluck('count', 'date');

            Cache::tags(['auctionDays'])->forever('dealer_'.$dealer->id, $auctionDays);
            return $auctionDays;
    }
    
    public function searchDealers($request, $addmap = false, $type = 'auctioneer')
    {
        
        if($dealers = Dealers::with('vehiclesCount')->SearchName($request->name)->SearchLocation($request->location)->searchAuctioneer($request->auctioneer)->get())
        {
            if($addmap) $this->addMap($dealers);
        }
        return $dealers;
    }
    
    private function getCachedDealers($type)
    {
        if (Cache::has('dealers_'.$type)) {
            return Cache::get('dealers_'.$type);
        }

        $dealers = Dealers::with('vehiclesCount')->where('type', $type)->orderBy('name', 'ASC')->get();
        Cache::forever('dealers_'.$type, $dealers);
        return $dealers;
    }

    private function getCachedDealersPaginate($type)
    {
        $page = Input::get('page', 1);

        if(\Auth::user()) {
            $cacheprefix = 'members';
        } else {
            $cacheprefix = 'public';
        }

        if (Cache::tags([$cacheprefix.'_dealers_'.$type])->has('page_'.$page)) {
            return Cache::tags([$cacheprefix.'_dealers_'.$type])->get('page_'.$page);
        }

        $dealers = Dealers::with('vehiclesCount')->where('type', $type)->orderBy('name', 'ASC')->paginate(20);
        Cache::tags([$cacheprefix.'_dealers_'.$type])->forever('page_'.$page, $dealers);
        return $dealers;
    }

    public function getDealerCounty($type='auctioneer')
    {
        return Cache::rememberForever('dealer_'.$type.'_county', function () use($type) {
                return Dealers::where('type', $type)->where('county', '!=', 'null')->where('county', '!=', '0')->where('county', '!=', '')->groupBy('county')->pluck('county', 'county')->toArray();
        });
    }

    public function getDealer($slug)
    {
        if (Cache::tags(['dealer_details'])->has('dealer_'.$slug)) {
            $dealer = Cache::tags(['dealer_details'])->get('dealer_'.$slug);
        } else {
            $dealer = Dealers::with('vehiclesCount')->where('slug', $slug)->firstOrFail();
            Cache::tags(['dealer_details'])->forever('dealer_'.$slug, $dealer);
        }

        $this->addDealerMap($dealer);
        $this->addDealerMap($dealer, true);
        return $dealer;
    }

    public function getDealerById($id, $classified=false)
    {

        if (Cache::tags(['dealer_details'])->has('dealer_'.$id)) {
            $dealer = Cache::tags(['dealer_details'])->get('dealer_'.$id);
        } else {
            $dealer = Dealers::with('vehiclesCount')->where('id', $id)->firstOrFail();
            Cache::tags(['dealer_details'])->forever('dealer_'.$id, $dealer);
        }

        if(!$classified)
        {
            $this->addDealerMap($dealer);
            $this->addDealerMap($dealer, true);
        }

        return $dealer;
    }

    public function addDealerMap($dealer, $widget=false, $useCity=null)
    {
        if(!$useCity)
        {
            if(isset($dealer->longitude) && isset($dealer->latitude))
            {
                if($widget)
                {
                    $this->addSingleMap($dealer, 8);
                } else {
                    if($dealer->has_streetview)
                    {
                        Mapper::streetview($dealer->latitude, $dealer->longitude, 1, 1);
                    } else {
                        $this->addSingleMap($dealer);
                    }
                }

            }
        } else {
            if(isset($dealer->city) && $dealer->city != '')
            {
                if($widget)
                {
                    $this->addSingleMapLocation($dealer, 9);
                } else {
                    $this->addSingleMapLocation($dealer);
                }

            }
        }

    }

    private function addMap($dealers, $location=null, $zoom=6)
    {
        Mapper::location('Leicester')->map(['zoom' => $zoom, 'marker' => false]);
         foreach ($dealers as $dealer)
        {
            $this->addMapItem($dealer);
        }
    }

    private function addSingleMap($dealer, $zoom=15)
    {
        Mapper::map($dealer->latitude, $dealer->longitude, ['zoom' => $zoom, 'marker' => false]);
        $this->addMapItem($dealer);
    }

    private function addSingleMapLocation($dealer, $zoom=15)
    {
        //$dealer->city = 'Sheerness';
        //$dealer->county = 'Devon';
        try {
            if($dealer->city != '' && $dealer->county != '')
            {
                Mapper::location(ucfirst(strtolower($dealer->city)) . ',' . ucfirst(strtolower($dealer->county)))->map(['zoom' => $zoom, 'marker' => false]);
            } elseif($dealer->city != '' && $dealer->county == ''){
                Mapper::location(ucfirst(strtolower($dealer->city)))->map(['zoom' => $zoom, 'marker' => false]);
            }
        } catch (\Exception $e) {
        }

    }

    private function addMapItem($dealer)
    {

        if(isset($dealer->longitude) && isset($dealer->latitude))
        {
            if($dealer->longitude == '') return;
            if($dealer->latitude == '') return;
            if($dealer->logo != '')
            {
                $content = '<div class="text-center"><a href="'.url('/members/auctioneer/'.$dealer->slug).'"><img alt="$dealer->name" src="'.url('/images/dealers/'.$dealer->id.'/thumb150-'.$dealer->logo).'"></a><br><a href="'.url('/members/auctioneer/'.$dealer->slug).'">'.$dealer->name.'</a></div>';
            } else {
                $content = '<div class="text-center"><a href="'.url('/members/auctioneer/'.$dealer->slug).'">'.$dealer->name.'</a></div>';
            }

            Mapper::informationWindow($dealer->latitude, $dealer->longitude, $content, ['markers' => ['title' => $dealer->name, 'animation' => 'DROP']]);
        }
    }

    public function dealerSelectList($type='all')
    {
        if($type == 'all')
        {
            return Cache::rememberForever('dealer_list_'.$type, function () use ($type) {
                return Dealers::orderBy('name', 'ASC')->pluck('name', 'slug');
            });
        }
        return Cache::rememberForever('dealer_list_'.$type, function () use ($type) {
            return Dealers::where('type', $type)->orderBy('name', 'ASC')->pluck('name', 'slug');
        });
    }
    
    public function dealerCarImages($dealer)
    {
        if (Cache::tags(['dealer_vehicle_images'])->has('dealer_'.$dealer->id)) {
            return Cache::tags(['dealer_vehicle_images'])->get('dealer_'.$dealer->id);
        }
        $vehicles = Vehicles::with('media')->select(['id', 'dealer_id', 'name', 'slug', 'images'])->where('dealer_id', $dealer->id)->orderBy('created_at', 'DESC')->limit(30)->get();

        /*$vehicles = $vehicles->filter( function($vehicle)
        {

            if($vehicle->media->count() > 0)
            {
                if(vehicleImageExists($vehicle->media->first()->name, $vehicle->id))
                {
                    return true;
                }
            }
        });*/

        Cache::tags(['dealer_vehicle_images'])->forever('dealer_'.$dealer->id, $vehicles);
        return $vehicles;
    }
 
}
