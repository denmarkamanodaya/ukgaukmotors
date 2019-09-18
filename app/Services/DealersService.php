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
use App\Models\DealersUser;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Cache;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\Input;
use Quantum\base\Models\Categories;

class DealersService
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

    
    public function searchDealers($request, $addmap = false, $type = 'auctioneer')
    {
        
        if($dealers = $this->cachedSearchDealers($request, $type))
        {
            if($addmap) $this->addMap($dealers);
        }
        return $dealers;
    }

    public function cachedSearchDealers($request, $type = 'auctioneer')
    {
        $page = Input::get('page', 1);
        if(\Auth::user()) {
            $cacheprefix = 'members';
        } else {
            $cacheprefix = 'public';
        }

        $cacheKey = md5($request->name.$request->location.$request->auctioneer.serialize($request->categories)).$page;

        if (Cache::tags([$cacheprefix.'_search_'.$type])->has($cacheKey)) {
            return Cache::tags([$cacheprefix.'_search_'.$type])->get($cacheKey);
        }


        $dealers = Dealers::SearchName($request->name)->SearchLocation($request->location)->searchAuctioneer($request->auctioneer)->searchCategories($request->categories)->orderBy('name', 'ASC')->paginate(20);
        Cache::tags([$cacheprefix.'_search_'.$type])->put($cacheKey, $dealers, 10);
        return $dealers;
    }
    
    private function getCachedDealers($type)
    {
        if (Cache::has('dealers_'.$type)) {
            return Cache::get('dealers_'.$type);
        }

        $dealers = Dealers::where('type', $type)->orderBy('name', 'ASC')->get();
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

        $dealers = Dealers::where('type', $type)->orderBy('name', 'ASC')->paginate(20);
        Cache::tags([$cacheprefix.'_dealers_'.$type])->forever('page_'.$page, $dealers);
        return $dealers;
    }

    public function getDealerCounty($type='auctioneer')
    {
        return Cache::rememberForever('dealer_'.$type.'_county', function () use($type) {
                return Dealers::where('type', $type)->where('county', '!=', 'null')->where('county', '!=', '0')->where('county', '!=', '')->groupBy('county')->pluck('county', 'county')->toArray();
        });
    }

    public function getDealer($slug, $addMap=true)
    {
        if (Cache::tags(['dealer_details'])->has('dealer_'.$slug)) {
            $dealer = Cache::tags(['dealer_details'])->get('dealer_'.$slug);
        } else {
            $dealer = Dealers::with('media', 'categories', 'categories.parent', 'features')->where('slug', $slug)->firstOrFail();
            Cache::tags(['dealer_details'])->forever('dealer_'.$slug, $dealer);
        }
        if($addMap)
        {
            $this->addDealerMap($dealer);
            $this->addDealerMap($dealer, true);
        }

        return $dealer;
    }

    public function getDealerById($id, $classified=false)
    {

        if (Cache::tags(['dealer_details'])->has('dealer_'.$id)) {
            $dealer = Cache::tags(['dealer_details'])->get('dealer_'.$id);
        } else {
            $dealer = Dealers::where('id', $id)->firstOrFail();
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
        try {
            if($dealer->city != '' && $dealer->county != '')
            {
                Mapper::location(ucfirst(strtolower($dealer->city)) . ',' . ucfirst(strtolower($dealer->county)))->map(['zoom'   => $zoom, 'marker' => false]);
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
            if($dealer->longitude == '' || is_null($dealer->longitude)) return;
            if($dealer->latitude == '' || is_null($dealer->latitude)) return;
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

    public function cachedCategoryList()
    {
        return Cache::rememberForever('categoryList', function () {
            $categories = Categories::whereHas('children')->with(['children' => function($query) {
                $query->where('slug', '!=', 'uncategorised')
                    ->orderBy('name', 'ASC');
            }])->where('name', '!=', 'Blog')->orderBy('name', 'ASC')->get();

            $catList = [];
            foreach ($categories as $category)
            {
                $catList[$category->name] = $category->children->pluck('name', 'id');
            }
            return $catList;
        });
    }

    public function cachedSearchCategories($request)
    {
        $cachekey = md5(serialize($request->categories));
        if (Cache::tags(['search_categories'])->has($cachekey)) {
            $searchCategories = Cache::tags(['search_categories'])->get($cachekey);
        } else {
            $searchCategories = Categories::whereIn('id', $request->categories)->pluck('name');
            Cache::tags(['search_categories'])->put($cachekey, $searchCategories, 10);
        }
        return $searchCategories;
    }

    public function userFavouriteToggle($dealerId)
    {
        $dealer = $this->getDealer($dealerId, false);
        $user = \Auth::user();

        if(DealersUser::where('user_id', $user->id)->where('dealers_id', $dealer->id)->first())
        {
            DealersUser::where('user_id', $user->id)->where('dealers_id', $dealer->id)->delete();
            $message['type'] = 'remove';
            $message['message'] = 'Dealer removed from favourites';
        } else {
            DealersUser::create([
                'user_id' => $user->id,
                'dealers_id' => $dealer->id
            ]);
            $message['type'] = 'add';
            $message['message'] = 'Dealer added to favourites';
        }
        \Cache::forget('favDealer_'.$user->id);
        return $message;
    }



    public function clearCache()
    {
        \App\Services\CacheService::clearDealers();
    }
 
}