@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Calendar' => '/admin/calendar', 'Import' => '/admin/calendar/import', 'Import Post' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Calendar Import Post</span>
@stop


@section('content')

    <div class="row">

        <div class="col-lg-8 col-md-offset-2">

            <div class="panel panel-flat border-top-info border-bottom-info">
                <div class="panel-heading">
                    <h6 class="panel-title">Dealer :  {{$dealer->name}}</h6>
                </div>

                <div class="panel-body">
                    <a target="_blank" href="{{ url('admin/auctioneer/'.$dealer->slug.'/events/')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-calendar-alt"></i></b> View Dealer Calendar Events</a>
                    @if($dealer->website && $dealer->website != '')
                        <a target="_blank" href="{{$dealer->website}}" class="btn bg-success btn-labeled ml-20" type="button"><b><i class="far fa-gavel"></i></b> Visit Dealer</a>
                    @endif
                </div>
            </div>

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <div class="heading-elements">

                    </div>
                </div>

                <div class="panel-body">

                    {!! Form::model($dealer, array('method' => 'POST', 'url' => '/admin/auctioneer/'.$dealer->slug.'/event/create')) !!}
                    {!! Form::hidden('import', $post->id) !!}


                    @include('calendar::partials.createForm')

                    {!! Form::close() !!}


                </div>
            </div>


            <div class="panel panel-flat border-top-info border-bottom-info">
                <div class="panel-heading">
                    <h6 class="panel-title">No Event Needed ?</h6>
                </div>

                <div class="panel-body">
                    <p>If you do not want to create an event with this import profile then hit the below button to remove it from the import list.</p>
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/calendar/import/markComplete')) !!}
                    {!! Form::hidden('import', $post->id) !!}
                    <button type="submit" class="btn btn-info">Only Mark Event As Complete<i class="far fa-check position-right"></i></button>
                    {!! Form::close() !!}
                </div>
            </div>


        </div>

    </div>



@stop


