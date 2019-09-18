<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : CalendarService.php
 **/

namespace Quantum\calendar\Services;


use Cache;
use Carbon\Carbon;
use Quantum\base\Models\Categories;
use Quantum\calendar\Models\Calendar;
use Quantum\calendar\Models\CalendarMeta;
use Quantum\calendar\Models\CalendarSubscriptions;
use Yajra\DataTables\Facades\DataTables;
use Quantum\base\Models\Postcode;

class CalendarService
{

    protected $startDate;
    protected $endDate;
    protected $startTime;
    protected $endTime;
    public$repeat_type = null;
    public $repeat_year = null;
    protected $repeat_month = null;
    protected $repeat_week = null;
    protected $repeat_day = null;
    protected $repeat_weekday = null;
    protected $repeat_amount = null;
    protected $eventTypes = [];

    public function addEventType($type, $ids=null)
    {
        $viewEvent['type'] = $type;
        $viewEvent['id'] = $ids;
        array_push($this->eventTypes, $viewEvent);
    }

    public function getDay($caldate=null, $filters=null)
    {
        if(!$caldate)
        {
            $carbon = Carbon::now();
        } else {
            $carbon = Carbon::createFromFormat('Y-n-j', $caldate);
            if(!$carbon) return false;
        }
        $cacheKey['c'] = $carbon->format('d-m-Y');
        $cacheKey['e'] = $this->eventTypes;
        $cacheKey['f'] = isset($filters) ? $filters: 0;
        $cacheKey = md5(serialize($cacheKey));

        $useTags = false;

        if(Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            $useTags = true;
        }

        if($useTags)
        {
            if (Cache::tags(['calendar'])->has($cacheKey)) {
                return Cache::tags(['calendar'])->get($cacheKey);
            }
        } else {
            if (Cache::has('Cal_'.$cacheKey)) {
                return Cache::get('Cal_'.$cacheKey);
            }
        }


        $events = Calendar::with(['meta', 'cal_eventable', 'categories'])
            ->where(function($query) use ($carbon, $filters){
                //year
               $query->where(function($query) use ($carbon){
                   $query->where('repeat_year', $carbon->year);
                   $query->orWhere('repeat_year', '*');
                });
               //month
                $query->where(function($query) use ($carbon){
                    $query->where('repeat_month', 'LIKE', '%'.$carbon->format('m').'%');
                    $query->orWhere('repeat_month', '*');
                });
                //day
                $query->where(function($query) use ($carbon){
                    $query->where('repeat_day', $carbon->day);
                    $query->orWhere('repeat_day', '*');
                });
                //week
                $query->where(function($query) use ($carbon) {
                    $query->where('repeat_week', 'LIKE', '%'.$carbon->weekOfMonth.'%');
                    $query->orWhere('repeat_week', '*');
                });
                //weekdays
                $query->where(function($query) use ($carbon) {
                    $query->where('repeat_weekday', 'LIKE', '%'.$carbon->dayOfWeek.'%');
                    $query->orWhere('repeat_weekday', '*');
                });
                //repeat limit
                $query->where(function($query) use ($carbon) {
                    $query->whereNull('repeat_finished');
                    $query->orWhereDate('repeat_finished', '>=', $carbon->toDateString());
                });

                //event types
                $query->where(function($query) use ($carbon) {
                    $query->whereNull('cal_eventable_type');
                    $this->setType($query);
                });
                //categories
                $query->where(function($query) use ($carbon, $filters) {
                    $this->setCategories($query, $filters);
                });

                $query->whereNull('user_id');
                $query->whereDate('start_day', '<', $carbon->toDateString());
                $query->tenant();
                $query->where('status', 'active');
            })

            ->orWhere(function($query) use ($carbon, $filters){
                $query->whereNull('user_id');
                //event types
                $query->where(function($query) {
                    $query->whereNull('cal_eventable_type');
                    $this->setType($query);
                });
                //categories
                $query->where(function($query) use ($carbon, $filters) {
                    $this->setCategories($query, $filters);
                });

                $query->whereDate('start_day', '=', $carbon->toDateString());
                $query->tenant();
                $query->where('status', 'active');
            })
            ->orderBy('start_time', 'ASC')->get();


        if($useTags)
        {
            Cache::tags(['calendar'])->put($cacheKey, $events, 10);
        } else {
            Cache::put('Cal_'.$cacheKey, $events, 10);
        }

            return $events;
    }

    private function setCategories($query, $filters)
    {
        if($filters && is_array($filters) && count($filters) > 0)
        {
            $query->whereHas('categories', function ($query) use($filters) {
                $query->whereIn('id', $filters);
            });
        }
    }

    public function getMonthEvents($calDate=null, $filters=null)
    {
        $monthEvents = [];
        if(!$calDate)
        {
            $carbon = Carbon::now();
        } else {
            $carbon = Carbon::createFromFormat('Y-n-j', $calDate);
            if(!$carbon) return false;
        }

        $monthEnd = $carbon->endOfMonth()->format('d');

        $cacheKey['m'] = $carbon->format('Y-n');
        $cacheKey['e'] = $this->eventTypes;
        $cacheKey['f'] = isset($filters) ? $filters: 0;
        $cacheKey = md5(serialize($cacheKey));

        $useTags = false;

        if(Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            $useTags = true;
        }

        if($useTags)
        {
            if (Cache::tags(['calendar_month'])->has($cacheKey)) {
                return Cache::tags(['calendar_month'])->get($cacheKey);
            }
        } else {
            if (Cache::has('Cal_Mon_'.$cacheKey)) {
                return Cache::get('Cal_Mon_'.$cacheKey);
            }
        }

        $monthEvents['year'] = $carbon->format('Y');
        $monthEvents['month'] = $carbon->format('n');

        for ($i = 1; $i <= $monthEnd; $i++) {
            $searchDate = $carbon->format('Y-n').'-'.$i;
            $events = $this->getDay($searchDate, $filters);
            $grouped = $events->groupBy('cal_eventable_type');
            $monthEvents['dates'][$i] = [];
            $monthEvents['dates'][$i]['total'] = ($events) ? $events->count() : 0;
            $groups = [];
            foreach($grouped as $group => $items)
            {
                if($group == '') $group = 'Site';
                array_push($groups, class_basename($group));
            }
            $monthEvents['dates'][$i]['event_types'] = $groups;
        }

        if($useTags)
        {
            Cache::tags(['calendar_month'])->forever($cacheKey, $monthEvents);
        } else {
            Cache::put('Cal_Mon_'.$cacheKey, $monthEvents, 60);
        }
        //dd($calDate, $carbon, $monthEnd, $monthEvents);
        return $monthEvents;
    }

    public function formatMonthEvents($events)
    {
        $thisEvents = [];
        foreach ($events['dates'] as $day => $dayEvents)
        {
            if($dayEvents['total'] == 0) continue;
            $ev = '';
            foreach($dayEvents['event_types'] as $key => $evType)
            {
                $evTypeCol = config('calendar.events.'.$evType, 'Grey');
                $ev .= $evTypeCol.',';
            }
            $thisEvents[$day] = rtrim($ev, ',');
        }
        return $thisEvents;
    }

    private function setType($query)
    {
        if(is_array($this->eventTypes) && count($this->eventTypes) > 0)
        {
            foreach ($this->eventTypes as $eventType)
            {
                if(is_array($eventType))
                {
                    $query->orWhere(function($query) use ($eventType) {
                        $query->where('cal_eventable_type', $eventType['type']);
                        if(!is_null($eventType['id']))
                        {
                            if(is_array($eventType['id'])) {
                                $query->whereIn('cal_eventable_id', $eventType['id']);
                            } else {
                                $query->where('cal_eventable_id', $eventType['id']);
                            }
                        }
                    });
                }
            }
        }
    }

    private function setupEvent($request)
    {
        $this->startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
        if(!isset($request->end_date) || $request->end_date == '' ){
            $this->endDate = $this->startDate;
        }else {
            $this->endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
        }

        if($request->all_day_event == 'no')
        {
            $this->startTime = Carbon::createFromFormat('H:i', $request->start_time);
            if($request->end_time && $request->end_time != '') {
                $this->endTime = Carbon::createFromFormat('H:i', $request->end_time);
            } else {
                $this->endTime = $this->startTime;
            }
        } else {
            $this->startTime = Carbon::createFromFormat('H:i:s', '00:00:01');
            $this->endTime = Carbon::createFromFormat('H:i:s', '23:59:59');
        }



        if($request->repeat_event == 'yes')
        {

            $this->repeat_month = isset($request->repeat_months) ? implode(',',$request->repeat_months) : '*';
            $this->repeat_week = isset($request->repeat_weeks) ? implode(',',$request->repeat_weeks) : '*';
            $this->repeat_weekday = isset($request->repeat_days) ? implode(',',$request->repeat_days) : $this->startDate->dayOfWeek;
            $this->repeat_amount = (isset($request->repeat_amount) && $request->repeat_amount > 0) ? $request->repeat_amount : null;

            $this->repeat_type = $request->repeat_type;

            if($request->repeat_type == 'daily')
            {
                $this->repeat_year = '*';
                $this->repeat_month = '*';
                $this->repeat_week = '*';
                $this->repeat_day = '*';
                $this->repeat_weekday = '*';
            }

            if($request->repeat_type == 'weekly')
            {
                $this->repeat_year = '*';
                $this->repeat_month = '*';
                $this->repeat_week = '*';
                $this->repeat_day = '*';
                //$repeat_weekday = $startDate->dayOfWeek;
            }

            if($request->repeat_type == 'monthly')
            {
                $this->repeat_year = '*';
                $this->repeat_month = '*';
                //$this->repeat_week = $this->startDate->weekOfMonth;
                $this->repeat_day = '*';
                //$repeat_weekday = $startDate->dayOfWeek;
            }

            if($request->repeat_type == 'yearly')
            {
                $this->repeat_year = '*';
                //$this->repeat_month = $this->startDate->month;
                //$this->repeat_week = $this->startDate->weekOfMonth;
                $this->repeat_day = '*';
                //$repeat_weekday = $startDate->dayOfWeek;
            }

        }
    }

    public function createEvent($request, $processExtra=true)
    {
        $this->setupEvent($request);

        $longitude  = $request->longitude;
        $latitude   = $request->latitude;

        if(($request['postcode'] != '' && is_null($longitude)) || ($request['postcode'] != '' && is_null($latitude)))
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }

        $event = Calendar::create([
            'title' => $request->title,
            'start_day' => $this->startDate,
            'start_time' => $this->startTime,
            'end_day' => $this->endDate,
            'end_time' => $this->endTime,
            'repeat_type' => $this->repeat_type,
            'repeat_year' => $this->repeat_year,
            'repeat_month' => $this->repeat_month,
            'repeat_day' => $this->repeat_day,
            'repeat_week' => $this->repeat_week,
            'repeat_weekday' => $this->repeat_weekday,
            'repeat_amount' => $this->repeat_amount,
            'status' => 'active'
        ]);

        CalendarMeta::create([
            'calendar_events_id' => $event->id,
            'description' => $request->description,
            'address' => $request->address,
            'county' => $request->county,
            'country_id' => $request->country_id,
            'postcode' => $request->postcode,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'event_image' => $request->event_image,
            'event_url' => $request->event_url,
        ]);

        if($request->category && $request->category > 0) $event->categories()->sync($request->category);

        flash('Event has been created.')->success();
        \Activitylogger::log('Created Event : '.$event->title, $event);

        if($processExtra)
        {
            if($request->extra_start_dates && count($request->extra_start_dates) > 1)
            {
               foreach($request->extra_start_dates as $key => $start_date)
               {
                   if($key == 0) continue;
                   $request->merge(['start_date' => $start_date]);
                   $this->createEvent($request, false);
               }
            }
        }

        $this->cacheClear();

    }

    public function createEventFromTrait($request, $model, $processExtra=true)
    {
        $this->setupEvent($request);

        $longitude  = $request->longitude;
        $latitude   = $request->latitude;

        if(($request['postcode'] != '' && is_null($longitude)) || ($request['postcode'] != '' && is_null($latitude)))
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }

        $event = new Calendar;
        $event->title = $request->title;
        $event->start_day = $this->startDate;
        $event->start_time = $this->startTime;
        $event->end_day = $this->endDate;
        $event->end_time = $this->endTime;
        $event->repeat_type = $this->repeat_type;
        $event->repeat_year = $this->repeat_year;
        $event->repeat_month = $this->repeat_month;
        $event->repeat_day = $this->repeat_week;
        $event->repeat_week = $this->repeat_day;
        $event->repeat_weekday = $this->repeat_weekday;
        $event->repeat_amount = $this->repeat_amount;
        $event->status = 'active';
        $event->save();

        $model->calendarEvents()->save($event);

        CalendarMeta::create([
            'calendar_events_id' => $event->id,
            'description' => $request->description,
            'address' => $request->address,
            'county' => $request->county,
            'country_id' => $request->country_id,
            'postcode' => $request->postcode,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'event_image' => $request->event_image,
            'event_url' => $request->event_url,
        ]);

        if($request->category && $request->category > 0) $event->categories()->sync($request->category);

        flash('Event has been created.')->success();
        \Activitylogger::log('Created Event : '.$event->title, $event);

        if($processExtra)
        {
            if($request->extra_start_dates && count($request->extra_start_dates) > 1)
            {
                foreach($request->extra_start_dates as $key => $start_date)
                {
                    if($key == 0) continue;
                    $request->merge(['start_date' => $start_date]);
                    $this->createEventFromTrait($request, $model, false);
                }
            }
        }

        $this->cacheClear();
        return $event;
    }

    public function updateEvent($request, $event)
    {
        if (!$event instanceof \Quantum\calendar\Models\Calendar) {
            $event = $this->getEvent($event);
        }

        $this->setupEvent($request);

        $longitude  = $request->longitude;
        $latitude   = $request->latitude;

        if(($request['postcode'] != '' && $longitude == '') || ($request['postcode'] != '' && $latitude == ''))
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }

        $event->title = $request->title;
        $event->start_day = $this->startDate;
        $event->start_time = $this->startTime;
        $event->end_day = $this->endDate;
        $event->end_time = $this->endTime;
        $event->repeat_type = $this->repeat_type;
        $event->repeat_year = $this->repeat_year;
        $event->repeat_month = $this->repeat_month;
        $event->repeat_day = $this->repeat_day;
        $event->repeat_week = $this->repeat_week;
        $event->repeat_weekday = $this->repeat_weekday;
        $event->repeat_amount = $this->repeat_amount;
        $event->save();

        $event->meta->description = $request->description;
        $event->meta->address = $request->address;
        $event->meta->county = $request->county;
        $event->meta->country_id = $request->country_id;
        $event->meta->postcode = $request->postcode;
        $event->meta->latitude = $latitude;
        $event->meta->longitude = $longitude;
        $event->meta->event_image = $request->event_image;
        $event->meta->event_url = $request->event_url;
        $event->meta->save();

        if($request->category && $request->category > 0) $event->categories()->sync($request->category);

        flash('Event has been updated.')->success();
        \Activitylogger::log('Updated Event : '.$event->title, $event);

            if($request->extra_start_dates && count($request->extra_start_dates) > 1)
            {
                foreach($request->extra_start_dates as $key => $start_date)
                {
                    if($key == 0) continue;
                    $request->merge(['start_date' => $start_date]);
                    $this->cloneEvent($request, $event);
                }
            }


        $this->cacheClear();

    }

    public function cloneEvent($request, $event)
    {
        $newEvent = $event->replicate();

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
        if(!isset($request->end_date) || $request->end_date == '' ){
            $endDate = $startDate;
        }else {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
            if($startDate->gt($endDate)) $endDate = $startDate;
        }

        $newEvent->start_day = $startDate;
        $newEvent->end_day = $endDate;
        $newEvent->save();
        $newEvent->meta = $event->meta->replicate();
        $newEvent->meta->calendar_events_id = $newEvent->id;
        $newEvent->meta->save();
        if($request->category && $request->category > 0) $newEvent->categories()->sync($request->category);
        flash('Event has been created.')->success();
        \Activitylogger::log('Created Event : '.$newEvent->title, $newEvent);
    }

    public function getEvents()
    {
        $events = Calendar::tenant()->select(['title', 'id', 'updated_at', 'slug', 'start_day', 'start_time', 'end_time', 'repeat_year']);
        return Datatables::eloquent($events)
            ->editColumn('updated_at', function ($model) {
                return [
                    'display' => e(
                        $model->updated_at->diffForHumans()
                    ),
                    'timestamp' => $model->updated_at->timestamp
                ];
            })
            ->editColumn('start_day', function ($model) {
                return [
                    'display' => e(
                        $model->start_day->toFormattedDateString()
                    ),
                    'timestamp' => $model->start_day->timestamp
                ];
            })
            ->editColumn('title', function ($model) {
                return '<a href="'. url('/admin/calendar/event/'.$model->slug) .'">'.$model->title.'</a>';
            })
            ->editColumn('repeat_year', function ($model) {
                if(!is_null($model->repeat_year)) return '<i class="far fa-check"></i>';
                return '';
            })
            ->rawColumns(['title', 'repeat_year'])
            ->make(true);
    }

    public function getEvent($slug)
    {
        return Calendar::with(['meta', 'cal_eventable', 'categories'])->tenant()->where('slug', $slug)->firstOrFail();
    }

    public function deleteEvent($event)
    {
        if (!$event instanceof \Quantum\calendar\Models\Calendar) {
            $event = $this->getEvent($event);
        }
        $event->meta->delete();
        $event->delete();
        flash('Event has been deleted.')->success();
        \Activitylogger::log('Deleted Event : '.$event->title, $event);
        $this->cacheClear();
    }

    public function dailyUpdate()
    {
        $events = $this->getDay();
        if(!$events) return;

        foreach ($events as $event)
        {
            if(is_null($event->repeat_year)) continue;
            if(!is_null($event->repeat_amount))
            {
                $now = Carbon::now();
                if($now->eq($event->start_day)) continue;
                $event->repeated++;
                if($event->repeated >= $event->repeat_amount) $event->repeat_finished = $now;
                $event->save();
            }
        }
        $this->cacheClear();
    }


    public function cacheClear()
    {
        $useTags = false;

        if(Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            $useTags = true;
        }

        if($useTags) {
            Cache::tags(['calendar'])->flush();
            Cache::tags(['calendar_month'])->flush();
        }
    }

    public function getCategoryList()
    {
        if(!config('calendar.use_categories')) return false;
        $categories = config('calendar.category_slugs');
        if(count($categories) == 0) return false;
        $categories = config('calendar.categoryClass')::with(['children' => function($query){
            $query->orderBy('name', 'ASC');
        }])->whereHas('children')->whereIn('slug', $categories)->get();
        return $categories;
    }

}