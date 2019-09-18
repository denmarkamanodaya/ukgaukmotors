<?php

Route::group(['namespace' => 'Quantum\calendar\Http\Controllers', 'middleware' => ['web']], function()
{

//Member Routes
    Route::group(['namespace' => 'Members', 'middleware' => ['auth']], function()
    {
        if(config('calendar.members_routes'))
        {
            Route::post('members/calendar/getDaysEvents', ['as' => 'members_calendar_dayEvents', 'uses' => 'Calendar@getDay']);
            Route::post('members/calendar/getDaysEvents/monthly', ['as' => 'members_calendar_monthlyEvents', 'uses' => 'Calendar@getMonth']);
            Route::get('members/calendar', ['as' => 'members_calendar', 'uses' => 'Calendar@index']);
        }
    });

//Admin Routes
    Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function()
    {
        Route::get('admin/calendar/event/create', ['as' => 'admin_calendar_event_create', 'uses' => 'Calendar@create']);
        Route::post('admin/calendar/event/create', ['as' => 'admin_calendar_event_create', 'uses' => 'Calendar@store']);
        Route::get('admin/calendar/event/{event}', ['as' => 'admin_calendar_event_edit', 'uses' => 'Calendar@edit']);
        Route::post('admin/calendar/event/{event}/edit', ['as' => 'admin_calendar_event_edit', 'uses' => 'Calendar@update']);
        Route::post('admin/calendar/event/{event}/delete', ['as' => 'admin_calendar_event_edit', 'uses' => 'Calendar@delete']);


        Route::get('admin/calendar/events', ['as' => 'admin_calendar_events', 'uses' => 'Calendar@events']);
        Route::get('admin/calendar/eventsData', ['as' => 'admin_calendar_eventsData', 'uses' => 'Calendar@eventsData']);

        Route::post('admin/calendar/getDaysEvents', ['as' => 'admin_calendar_dayEvents', 'uses' => 'Calendar@getDay']);
        Route::post('admin/calendar/getDaysEvents/monthly', ['as' => 'admin_calendar_monthlyEvents', 'uses' => 'Calendar@getMonth']);
        Route::get('admin/calendar', ['as' => 'admin_calendar', 'uses' => 'Calendar@index']);

    });



});