@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    <script>
        var eventsUrl = '/admin/calendar/getDaysEvents';
    </script>
    <script type="text/javascript" src="{{url('assets/js/calendarDaily.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/jquery.mousewheel.js')}}"></script>
    <script>
        var calendarPicker = $("#calHeader").calendarPicker({
            monthNames:["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            dayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            //useWheel:true,
            //callbackDelay:500,
            years:2,
            months:4,
            days:4,
            showDayArrows:true,
            callback:function(cal) {
            }});
    </script>
@stop

@section('page_css')
    <link href="{{url('assets/css/calendarDaily.css')}}" rel="stylesheet" type="text/css">
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{config('calendar.google_api_key')}}">
    </script>
    <script>
        var mapArrays = [];
    </script>
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Calendar' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Calendar</span>
@stop


@section('content')

    <div class="row">

        <div class="col-lg-8 col-md-offset-2">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <div class="heading-elements">

                    </div>
                </div>

                <div class="panel-body">
                    <a href="{{url('/admin/calendar/event/create')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b><i class="far fa-calendar-alt"></i></b>Create New Site Event
                        </button>
                    </a>
                    <div id="calLegend" class="row" style="text-align: right;">
                        @include('calendar::partials.calLegend')
                        @include('calendar::partials.filters')
                    </div>
                    <div id="calHeader" class="row"></div>
                    <div id="calEvents" class="row"></div>

                </div>
            </div>

        </div>

    </div>



@stop


