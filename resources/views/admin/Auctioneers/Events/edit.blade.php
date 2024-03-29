@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{ url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/flatpickr/dist/flatpickr.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/calendar.js')}}"></script>
    <script>
        $('#eventDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this event?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });
    </script>
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ url('assets/js/flatpickr/dist/flatpickr.css')}}" />
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', $dealer->name => '/admin/dealers/auctioneer/'.$dealer->slug, 'Events' => '/admin/dealers/auctioneer/'.$dealer->slug.'/events', 'Create Event' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Event</span> - Edit event
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
                            {!! Form::model($event, array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$dealer->slug.'/event/'.$event->id.'/edit')) !!}


                            @include('calendar::partials.editForm')


                        </div>
                    </div>
                </div>
                {!! Form::close() !!}

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            Extra
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">


                            {!! Form::model($event, array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$dealer->slug.'/event/'.$event->id.'/delete', 'id' => 'eventDelete')) !!}
                            <button type="submit" class="btn btn-warning">Remove Event <i class="far fa-times position-right"></i></button>
                            {!! Form::close() !!}


                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {!! Shortcode::showModal() !!}
@stop


