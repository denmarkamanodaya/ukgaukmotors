<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : GarageService.php
 **/

namespace App\Services;


use App\Filters\GarageFilters;
use App\Filters\VehicleFilters;
use App\Models\GarageFeed;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Cache;
use Laracasts\Flash\Flash;
use Quantum\base\Models\News;
use Quantum\base\Services\NewsService;

class GarageService
{

    /**
     * @var DealerService
     */
    private $dealerService;
    /**
     * @var GarageFilters
     */
    private $garageFilters;
    /**
     * @var NewsService
     */
    private $newsService;

    public function __construct(DealerService $dealerService, GarageFilters $garageFilters, NewsService $newsService)
    {
        $this->dealerService = $dealerService;
        $this->garageFilters = $garageFilters;
        $this->newsService = $newsService;
    }

    public function getFeed($request)
    {
        $feed = GarageFeed::where('id', $request->feed)->where('user_id', \Auth::user()->id)->firstOrFail();

        if ($request->has('page')) {
            $page = $request->input('page');
        } else {
            $page = 1;
        }
        $feed->page = $page;
        $cacheKey = $this->buildCacheKey($feed, $page);

        if (Cache::tags(['garage_feed'])->has($cacheKey)) {
            $feed->vehicles = Cache::tags(['garage_feed'])->get($cacheKey);
            return $feed;
        }

        $query = Vehicles::query();
        $query->with('media');
        $this->garageFilters->setBuilder($query);
        if($feed->search) $query = $this->garageFilters->search($feed->search);
        if($feed->auctioneer) $query = $this->garageFilters->auctioneer($feed->auctioneer);
        if($feed->location) $query = $this->garageFilters->location($feed->location);
        if($feed->vehicleMake) $query = $this->garageFilters->vehicleMake($feed->vehicleMake);
        if($feed->vehicleModel) $query = $this->garageFilters->vehicleModel($feed->vehicleModel);
        if($feed->auctionDay) $query = $this->garageFilters->auctionDay($feed->auctionDay);
        $query = $this->garageFilters->vehicle_listing_type($feed->vehicle_listing_type);
        $feed->vehicles = $query->orderBy('auction_date', 'ASC')->paginate(10);
        Cache::tags(['garage_feed'])->put($cacheKey, $feed->vehicles, 15);
        return $feed;
    }

    private function buildCacheKey($feed, $page)
    {
        $params = [];
        $params[0] = 'garageFeed';
        $params[1] = 'page='.$page;
        $cacheKey = '';
        if($feed->search && $feed->search != '') array_push($params, 'search='.urlencode($feed->search));
        if($feed->auctioneer && $feed->auctioneer != '0') array_push($params, 'auctioneer='.$feed->auctioneer);
        if($feed->location && $feed->location != '0') array_push($params, 'location='.$feed->location);
        if($feed->vehicleMake && $feed->vehicleMake != '0') array_push($params, 'vehicleMake='.$feed->vehicleMake);
        if($feed->vehicleModel && $feed->vehicleModel != '0') array_push($params, 'vehicleModel='.$feed->vehicleModel);
        if($feed->auctionDay && $feed->auctionDay != '0') array_push($params, 'auctionDay='.$feed->auctionDay);
        if($feed->vehicle_listing_type && $feed->vehicle_listing_type != '0') array_push($params, 'vehicle_listing_type='.$feed->vehicle_listing_type);
        foreach($params as $param)
        {
          $cacheKey .= $param;
        }
        $cacheKey = md5($cacheKey);
        return $cacheKey;
    }

    public function setFeedTitle($request)
    {
        $feed = GarageFeed::where('id', $request->id)->where('user_id', \Auth::user()->id)->firstOrFail();
        $feed->title = $request->title;
        $feed->save();

        //do we have a parent
        if($feed->parent_id && $feed->parent_id > 0) {
            if($pfeed = GarageFeed::where('user_id', \Auth::user()->id)->where('id', $feed->parent_id)->first())
            {
                $pfeed->title = $request->title;
                $pfeed->save();
            }
        } else {
            //change children
            if($feed2 = GarageFeed::where('user_id', \Auth::user()->id)->where('parent_id', $feed->id)->get())
            {
                foreach ($feed2 as $extrafeed)
                {
                    $extrafeed->title = $request->title;
                    $extrafeed->save();
                }
            }
        }
        Flash::success('Feed title has been updated.');
        \Activitylogger::log('Updated feed title : '.$feed->title, $feed);
    }

    public function removeFeed($id)
    {

        $id = filter_var($id, FILTER_SANITIZE_STRING);
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            Flash::error('There was a problem removing this feed.');
            return;
        }

        $feed = GarageFeed::where('id', $id)->where('user_id', \Auth::user()->id)->firstOrFail();
        $feed->delete();
        if($feed->parent_id && $feed->parent_id > 0) GarageFeed::where('id', $feed->parent_id)->where('user_id', \Auth::user()->id)->delete();
        GarageFeed::where('parent_id', $feed->id)->where('user_id', \Auth::user()->id)->delete();
        Flash::success('Feed has been removed.');
        \Activitylogger::log('Removed feed : '.$feed->title, $feed);
    }

    public function addFeed($request, $ajax=false)
    {
        $user = \Auth::user();
        //$count = $user->garageFeed()->count();
        $count = GarageFeed::where('vehicle_listing_type', 1)->where('user_id', \Auth::user()->id)->count();
        if($count >= 10)
        {
            if(!$ajax) Flash::error('Sorry all your feed slots are currently in use.');
            $data['type'] = 'error';
            $data['message'] = 'Sorry all your feed slots are currently in use.';
            return $data;
        }
        $position = 1;
        $feed = GarageFeed::where('user_id', $user->id)->orderBy('position', 'DESC')->first();
        if($feed) $position = $feed->position + 1;

        $feed = $user->garageFeed()->create([
            'title' => $request->title,
            'search' => isset($request->search) && $request->search != '' ? $request->search : null,
            'auctioneer' => isset($request->auctioneer) && $request->auctioneer != '0' ? $request->auctioneer : null,
            'location' => isset($request->location) && $request->location != '0' ? $request->location : null,
            'vehicleMake' => isset($request->vehicleMake) && $request->vehicleMake != '0' ? $request->vehicleMake : null,
            'vehicleModel' => isset($request->vehicleModel) && $request->vehicleModel != '0' ? $request->vehicleModel : null,
            'auctionDay' => isset($request->auctionDay) && $request->auctionDay != '0' ? $request->auctionDay : null,
            'position' => $position,
            'notify' => 0,
            'vehicle_listing_type' => 1
        ]);

        $user->garageFeed()->create([
            'title' => $request->title,
            'search' => isset($request->search) && $request->search != '' ? $request->search : null,
            'auctioneer' => isset($request->auctioneer) && $request->auctioneer != '0' ? $request->auctioneer : null,
            'location' => isset($request->location) && $request->location != '0' ? $request->location : null,
            'vehicleMake' => isset($request->vehicleMake) && $request->vehicleMake != '0' ? $request->vehicleMake : null,
            'vehicleModel' => isset($request->vehicleModel) && $request->vehicleModel != '0' ? $request->vehicleModel : null,
            'auctionDay' => isset($request->auctionDay) && $request->auctionDay != '0' ? $request->auctionDay : null,
            'position' => $position,
            'notify' => 0,
            'vehicle_listing_type' => 2,
            'parent_id' => $feed->id
        ]);

        if(!$ajax) Flash::success('Feed has been created.');
        \Activitylogger::log('Created feed : '.$feed->title, $feed);
        $data['type'] = 'success';
        $data['message'] = 'Feed has been created.';
        return $data;
    }

    public function changePosition($request)
    {
        $user = \Auth::user();
        if($out = explode('&', $request->position))
        {
            foreach ($out as $key => $item)
            {
                $item = str_replace('feedCol[]=', '', $item);
                $id = filter_var($item, FILTER_SANITIZE_STRING);
                $id = filter_var($id, FILTER_VALIDATE_INT);
                if($id) {
                    GarageFeed::where('user_id', $user->id)->where('id', $id)->update(['position' => $key]);
                }
            }
        }
    }

    public function feedSetup($user)
    {
        if($this->seenTour($user, 'garage_feed_welcome')) return $user;
        $feed = $user->garageFeed()->create([
            'title' => 'Ford Fiesta',
            'vehicleMake' => 'ford',
            'vehicleModel' => '3512',
            'position' => 1,
            'notify' => 0,
            'vehicle_listing_type' => 1
        ]);
        $user->garageFeed()->create([
            'title' => 'Ford Fiesta',
            'vehicleMake' => 'ford',
            'vehicleModel' => '3512',
            'position' => 1,
            'notify' => 0,
            'vehicle_listing_type' => 2,
            'parent_id' => $feed->id
        ]);
        return $user;
    }

    private function seenTour($user, $tour)
    {
        if($user->tours->count() == 0) return false;

        foreach ($user->tours as $tour)
        {
            if($tour->name == $tour) return true;
        }
        return false;
    }

    public function hasSeenTour($request)
    {
        \Auth::user()->tours()->create([
            'name' => $request->tour
        ]);
    }

    public function getPageSnippet($premium)
    {
        if($premium) return $this->newsService->getSnippet('My Garage Index');
        return $this->newsService->getSnippet('My Garage Free Index');
    }

    public function getCalendarPageSnippet()
    {
        return $this->newsService->getSnippet('My Garage Calendar');
    }

    public function feedTypeAdjust()
    {
        $feeds = GarageFeed::all();

        foreach($feeds as $feed)
        {
            GarageFeed::create([
                'title' => $feed->title,
                'user_id' => $feed->user_id,
                'search' => $feed->search,
                'auctioneer' => $feed->auctioneer,
                'location' => $feed->location,
                'vehicleMake' => $feed->vehicleMake,
                'vehicleModel' => $feed->vehicleModel,
                'auctionDay' => $feed->auctionDay,
                'position' => $feed->position,
                'notify' => 0,
                'vehicle_listing_type' => 2,
                'parent_id' => $feed->id
            ]);
        }
    }


}