<?php

namespace Quantum\calendar\Http\Controllers\Members;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
        $this->calendarService->addEventType('App\User', \Auth::User()->id);
        $this->setDefaultEvents();
        $events = $this->calendarService->getMonthEvents();
        $eventMonth = $events['month'] -1;
        $thisEvents = $this->calendarService->formatMonthEvents($events);
        return view('calendar::members.index', compact('thisEvents', 'eventMonth'));
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

}
