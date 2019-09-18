@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    <script>
        var eventsUrl = '/members/calendar/getDaysEvents';
        var eventdates = {m_{{$eventMonth}}:{!! json_encode($thisEvents) !!}};
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
            days:5,
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
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Calendar' => 'is_current']) !!}
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

                    <div id="calLegend" style="width:100%; text-align: right;">
                       @include('calendar::partials.calLegend')
                    </div>
                    <div id="calHeader" style="width:100%"></div>
                    <div id="calEvents" style="width:100%"></div>

                </div>
            </div>

        </div>

    </div>



@stop


