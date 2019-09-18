<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dealers;
use App\Services\VehicleService;
use App\Http\Controllers\Controller;
use Quantum\base\Models\Countries;
use Quantum\calendar\Http\Requests\Admin\createCalendarEventRequest;
use Quantum\calendar\Services\CalendarService;

class AuctioneerEvents extends Controller
{

    /**
     * @var VehicleService
     */
    private $vehicleService;
    /**
     * @var CalendarService
     */
    private $calendarService;

    public function __construct(VehicleService $vehicleService, CalendarService $calendarService)
    {
        $this->vehicleService = $vehicleService;
        $this->calendarService = $calendarService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $dealer = Dealers::with('calendarEvents')->where('slug', $id)->firstOrFail();
        return view('admin.Auctioneers.Events.index', compact('dealer'));
    }


    public function edit($id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            $query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->first();
        $categories = $this->calendarService->getCategoryList();

        $event->all_day_event = is_null($event->start_time) ? 'yes' : 'no';
        $event->repeat_event = is_null($event->repeat_year) ? 'no' : 'yes';
        $countries = Countries::pluck('name', 'id');
        return view('admin.Auctioneers.Events.edit', compact('dealer', 'event', 'categories', 'countries'));
    }

    public function cloneEvent($id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            $query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->first();
        $categories = $this->calendarService->getCategoryList();

        $event->all_day_event = is_null($event->start_time) ? 'yes' : 'no';
        $event->repeat_event = is_null($event->repeat_year) ? 'no' : 'yes';
        $countries = Countries::pluck('name', 'id');
        return view('admin.Auctioneers.Events.cloneEvent', compact('dealer', 'event', 'categories', 'countries'));
    }

    public function create($id)
    {
        $dealer = Dealers::where('slug', $id)->firstOrFail();
        $categories = $this->calendarService->getCategoryList();
        $countries = Countries::pluck('name', 'id');
        return view('admin.Auctioneers.Events.create', compact('dealer', 'categories', 'countries'));
    }

    public function store(createCalendarEventRequest $request, $id)
    {
        $dealer = Dealers::where('slug', $id)->firstOrFail();
        $dealer->calendarEventCreate($request);
        \Cache::forget('auctioneers');
        if($request->import)
        {
            \Quantum\base\Models\Import::where('id', $request->import)->update(['complete' => 1]);
            flash('Event Imported')->success();
            return redirect('/admin/calendar/import');
        }
        return redirect('/admin/dealers/auctioneer/'.$id.'/events');
    }

    public function update(createCalendarEventRequest $request, $id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            $query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->first();
        $calendarService = new CalendarService();
        $calendarService->updateEvent($request, $event);
        \Cache::forget('auctioneers');
        return redirect('/admin/dealers/auctioneer/'.$id.'/events');
    }

    public function delete($id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            $query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->first();
        $calendarService = new CalendarService();
        $calendarService->deleteEvent($event);
        \Cache::forget('auctioneers');
        return redirect('/admin/dealers/auctioneer/'.$id.'/events');
    }
}
