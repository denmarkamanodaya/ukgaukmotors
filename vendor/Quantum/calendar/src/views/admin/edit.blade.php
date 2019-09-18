@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/flatpickr/dist/flatpickr.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/calendar.js')}}"></script>
@stop

@section('page_css')
    <link rel="stylesheet" href="{{url('assets/js/flatpickr/dist/flatpickr.css')}}" />
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Calendar' => '/admin/calendar', 'Events' => '/admin/calendar/events', 'Edit Event' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Event</span> - Edit event
@stop


@section('content')


    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @if($eventRelation && View::exists('calendar::admin.partials.'.$eventRelation))
                @include('calendar::admin.partials.'.$eventRelation)

            @else
                @include('calendar::admin.partials.SystemEvent')
            @endif

                {!! Form::model($event, array('method' => 'POST', 'url' => '/admin/calendar/event/'.$event->slug.'/edit')) !!}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">Event Details</a>
                        </h6>
                    </div>
                        <div class="panel-body">


                            @include('calendar::partials.editForm')


                        </div>
                </div>


            {!! Form::close() !!}

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Delete Event</h6>
                </div>

                <div class="panel-body">
                    To remove this event click on the following button
                    {!! Form::model($event, array('method' => 'POST', 'url' => '/admin/calendar/event/'.$event->slug.'/delete')) !!}
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning">Delete Event<i class="far fa-times position-right"></i></button>
                        </div>

                    </div>
                    {!! Form::close() !!}

                </div>
            </div>

        </div>

    </div>

    {!! Shortcode::showModal() !!}
@stop