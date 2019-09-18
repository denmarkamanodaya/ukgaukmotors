<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : CalEventable.php
 **/

namespace Quantum\calendar\Traits;

use Quantum\calendar\Services\CalendarService;

trait CalEventable
{
    public function calendarEvents()
    {
        return $this->morphMany(\Quantum\calendar\Models\Calendar::class, 'cal_eventable');
    }

    public function calendarSubscribe()
    {
        $userModel = config('auth.providers.users.model');
        return $this->morphedToMany($userModel, 'calendar_subable');
    }

    public function calendarEventCreate($request)
    {
        $calendarService = New CalendarService();
        $event = $calendarService->createEventFromTrait($request, $this);
        return $event;
    }
}