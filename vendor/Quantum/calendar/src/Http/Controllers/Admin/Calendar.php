<?php

namespace Quantum\calendar\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Models\Countries;
use Quantum\calendar\Http\Requests\Admin\createCalendarEventRequest;
use Quantum\calendar\Http\Requests\Admin\getEventDayRequest;
use Quantum\calendar\Http\Requests\Admin\getEventmonthRequest;
use Quantum\calendar\Services\CalendarService;


class Calendar extends Controller
{
    /**
     * @var CalendarService
     */
    private $calendarService;

    private $defaultEvents;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
        $this->defaultEvents = config('calendar.default_types');
    }

    private function setDefaultEvents()
    {
        if(count($this->defaultEvents) == 0) return;
        foreach($this->defaultEvents as $key => $values)
        {
            $this->calendarService->addEventType($key, $values);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(config('calendar.userEvent'))$this->calendarService->addEventType('App\User', \Auth::User()->id);
        $categories = $this->calendarService->getCategoryList();
        $this->setDefaultEvents();
        /*$events = $this->calendarService->getMonthEvents();
        $eventMonth = $events['month'] -1;
        $thisEvents = $this->calendarService->formatMonthEvents($events);*/
        return view('calendar::admin.index', compact('categories'));
    }

    public function getDay(getEventDayRequest $request)
    {
        \View::share('javascript', true);
        if(config('calendar.userEvent')) $this->calendarService->addEventType('App\User', \Auth::User()->id);
        $this->setDefaultEvents();
        $events = $this->calendarService->getDay($request->caldate, $request->filters);
        if($events)
        {
            $view = \View::make('calendar::admin.dailyEvent.index', compact('events'));
            $data['status'] = 'success';
            $data['events'] = $view->render();
        }
        return $data;

    }

    public function getMonth(getEventmonthRequest $request)
    {

        if(config('calendar.userEvent')) $this->calendarService->addEventType('App\User', \Auth::User()->id);
        $this->setDefaultEvents();
        $events = $this->calendarService->getMonthEvents($request->caldate, $request->filters);
        $thisEvents = $this->calendarService->formatMonthEvents($events);
        return $thisEvents;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->calendarService->getCategoryList();
        $countries = Countries::pluck('name', 'id');
        return view('calendar::admin.create', compact('categories', 'countries'));
    }

    public function store(createCalendarEventRequest $request)
    {
        $this->calendarService->createEvent($request);
        return redirect('/admin/calendar');
    }

    public function events()
    {
        return view('calendar::admin.events');
    }

    public function eventsData()
    {
        return $this->calendarService->getEvents();
    }

    public function edit($event)
    {
        $event = $this->calendarService->getEvent($event);
        $categories = $this->calendarService->getCategoryList();
        $eventRelation = null;
        if(!is_null($event->cal_eventable)) $eventRelation = class_basename(get_class($event->cal_eventable));

        $event->all_day_event = ($event->start_time == '00:00') ? 'yes' : 'no';
        $event->repeat_event = (is_null($event->repeat_year)) ? 'no' : 'yes';
        $event->repeat_type = (is_null($event->repeat_type)) ? 'weekly' : $event->repeat_type;
        $countries = Countries::pluck('name', 'id');
        return view('calendar::admin.edit', compact('event', 'eventRelation', 'categories', 'countries'));
    }

    public function update(createCalendarEventRequest $request, $event)
    {
        $this->calendarService->updateEvent($request, $event);
        return redirect('/admin/calendar/events');
    }

    public function delete($event)
    {
        $this->calendarService->deleteEvent($event);
        return redirect('/admin/calendar/events');
    }

}
