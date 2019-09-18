@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{ url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/flatpickr/dist/flatpickr.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/calendar.js')}}"></script>
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ url('assets/js/flatpickr/dist/flatpickr.css')}}" />
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', $dealer->name => '/admin/dealers/auctioneer/'.$dealer->slug, 'Events' => '/admin/dealers/auctioneer/'.$dealer->slug.'/events', 'Create Event' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Event</span> - Create a new event
@stop


@section('content')


    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('admin.Auctioneers.Events.partials.DealersHeader')

            <div id="accordion-control-right" class="panel-group panel-group-control panel-group-control-right content-group-lg">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">{{$dealer->name}} : Event Details</a>
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">
                            {!! Form::model($dealer, array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$dealer->slug.'/event/create')) !!}


                            @include('calendar::partials.createForm')


                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    {!! Form::close() !!}
    {!! Shortcode::showModal() !!}
@stop


