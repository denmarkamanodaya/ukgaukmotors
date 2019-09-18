@extends('base::members.Template')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        var eventsUrl = '/members/mygarage/calendar/getDaysEvents';
        var csrf_token = '{!! csrf_token() !!}';
    </script>
    <script type="text/javascript" src="{{ url('assets/js/calendarDaily.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/jquery.mousewheel.js')}}"></script>
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
    <link href="{{ url('assets/css/gaukCalendarDaily.css')}}" rel="stylesheet" type="text/css">
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{config('calendar.google_api_key')}}">
    </script>
    <script>
        var mapArrays = [];
        window.FontAwesomeConfig = {
            searchPseudoElements: true
        }
    </script>

@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'My Garage' => '/members/mygarage','My Calendar' => 'is_current']) !!}
@stop

@section('sidebarText')
@stop

@section('sidebarFooter')

@stop

@section('page-header')
    <span class="text-semibold">My Calendar</span>
@stop

@section('headerContent')
    @if($pageSnippet && $pageSnippet->header_content)
        <div style="margin-left: 15em">
            <div>
                {!! $pageSnippet->header_content !!}
            </div>
        </div>
    @endif
@stop

@section('content')

    @if($pageSnippet)
        <div class="row">
            <div class="col-md-12">{!! $pageSnippet->content !!}</div>
        </div>
    @endif

            <div class="row" style="">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 mb-20" style="margin-left: 16.66666667%">

                    <div id="calLegend" style="width:100%; text-align: right;">
                        @include('calendar::partials.calLegend')
                        @include('calendar::members.partials.filters')
                    </div>
                    <div id="calHeader" style="width:100%"></div>
                    <div id="calEvents" style="width:100%"></div>

                </div>

            </div>

@stop


