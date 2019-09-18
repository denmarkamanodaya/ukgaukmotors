<?php

namespace App\Http\Controllers\Members;

use App\Services\DealerService;
use App\Services\GarageService;
use App\Services\VehicleService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Quantum\calendar\Http\Requests\Admin\getEventDayRequest;
use Quantum\calendar\Http\Requests\Admin\getEventmonthRequest;
use Quantum\calendar\Services\CalendarService;

class MyGarage extends Controller
{

    /**
     * @var VehicleService
     */
    private $vehicleService;
    /**
     * @var GarageService
     */
    private $garageService;
    /**
     * @var DealerService
     */
    private $dealerService;
    /**
     * @var CalendarService
     */
    private $calendarService;

    private $defaultEvents;

    public function __construct(VehicleService $vehicleService, GarageService $garageService, DealerService $dealerService, CalendarService $calendarService)
    {
        $this->vehicleService = $vehicleService;
        $this->garageService = $garageService;
        $this->dealerService = $dealerService;
        $this->calendarService = $calendarService;
        $this->defaultEvents = config('calendar.default_types');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $premium = ($user->can('premium-access')) ? true : false;
        $pageSnippet = $this->garageService->getPageSnippet($premium);
        $shortlistCount = $user->shortlist()->count();
        $feedCount = $user->garageFeed()->count();
        return view('members.MyGarage.index', compact('pageSnippet', 'shortlistCount', 'feedCount'));

    }

    public function shortlist()
    {
        if(!\Auth::user()->hasRole(\Settings::get('main_content_role'))) abort(404);
        $vehicles = $this->vehicleService->getShortlistedVehicles();
        $shortlist = $this->vehicleService->userShortlist();
        $viewType = 'grid';
        return view('members.MyGarage.Shortlist.index', compact('vehicles', 'viewType', 'shortlist'));
    }

    public function feed()
    {
        $user = Auth::user();
        $user->load(['tours','garageFeed' => function($query){
            $query->with('auctioneerD', 'vehicleMakeD', 'vehicleModelD');
        }]);

        if($user->garageFeed->count() == 0)
        {
            $user = $this->garageService->feedSetup($user);
            $user->load(['garageFeed' => function($query){
                $query->with('auctioneerD', 'vehicleMakeD', 'vehicleModelD');
            }]);
        }

        $user->garageFeed = $user->garageFeed->groupBy('vehicle_listing_type');
        $seenWelcome = $user->tours->contains('name', 'garage_feed_welcome');

        $dealerList[0] = 'Company';
        $dealerList = array_merge($dealerList,$this->dealerService->dealerSelectList()->toArray());
        $dealerLocation[0] = 'Location';
        $dealerLocation = array_merge($dealerLocation,$this->dealerService->getDealerCounty());
        $vehicleMakes[0] = 'Make';
        $vehicleMakes = array_merge($vehicleMakes,$this->vehicleService->vehiclesMakeListCount());
        $auctionDays = $this->vehicleService->getAuctionDays();
        $vehicleModels[0] = 'Model';

        return view('members.MyGarage.Feed.index', compact('dealerList', 'dealerLocation', 'vehicleMakes', 'vehicleModels','auctionDays', 'seenWelcome'));
    }


    public function getFeed(Requests\Members\GarageFeedRequest $request)
    {
        $feed = $this->garageService->getFeed($request);
        $shortlist = $this->vehicleService->userShortlist();

        $view = \View::make('members.MyGarage.Feed.vehicles', compact('feed', 'shortlist'));
        $contents = $view->render();

        return $contents;
    }

    public function setFeedTitle(Requests\Members\SetFeedTitleRequest $request)
    {
        $this->garageService->setFeedTitle($request);
        return redirect('members/mygarage/feed');
    }

    public function removeFeed($id)
    {
        $this->garageService->removeFeed($id);
        return redirect('members/mygarage/feed');
    }

    public function addFeed(Requests\Members\AddFeedRequest $request)
    {
        $this->garageService->addFeed($request);
        return redirect('members/mygarage/feed');
    }

    public function changePosition(Request $request)
    {
        $this->garageService->changePosition($request);
        exit;
    }

    public function tourComplete(Requests\Members\TourCompleteRequest $request)
    {
        $this->garageService->hasSeenTour($request);
        exit;
    }

    private function setDefaultEvents()
    {
        if(count($this->defaultEvents) == 0) return;
        foreach($this->defaultEvents as $key => $values)
        {
            $this->calendarService->addEventType($key, $values);
        }
    }

    public function calendar()
    {
        //$allowed = [1,4];
        //if(in_array(\Auth::user()->id, $allowed))
        //{
            //$this->calendarService->addEventType('App\User', \Auth::User()->id);
            $this->setDefaultEvents();
            $categories = $this->calendarService->getCategoryList();
            $pageSnippet = $this->garageService->getCalendarPageSnippet();
            return view('members.MyGarage.Calendar.index', compact('categories', 'pageSnippet'));
        //}

        //return view('members.MyGarage.Calendar.underConstruction');

    }

    public function getDay(getEventDayRequest $request)
    {
        \View::share('javascript', true);
        $this->calendarService->addEventType('App\User', \Auth::User()->id);
        $this->setDefaultEvents();
        $events = $this->calendarService->getDay($request->caldate, $request->filters);
        if($events)
        {
            $view = \View::make('calendar::members.dailyEvent.index', compact('events'));
            $data['status'] = 'success';
            $data['events'] = $view->render();
        }
        return $data;

    }

    public function getMonth(getEventmonthRequest $request)
    {

        $this->calendarService->addEventType('App\User', \Auth::User()->id);
        $this->setDefaultEvents();
        $events = $this->calendarService->getMonthEvents($request->caldate, $request->filters);
        $thisEvents = $this->calendarService->formatMonthEvents($events);
        return $thisEvents;
    }

    public function addAjaxFeed(Requests\Members\AddFeedRequest $request)
    {
        $message = $this->garageService->addFeed($request, true);
        return json_encode($message);
    }
}
