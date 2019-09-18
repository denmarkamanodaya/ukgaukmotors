@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')

@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', $dealer->name => '/admin/dealers/auctioneer/'.$dealer->slug, 'Events' => 'is_current')) !!}

@stop

@section('page-header')
    <span class="text-semibold">Support</span>
@stop


@section('content')

    <div class="row">

        <div class="col-lg-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">{{$dealer->name}} Events</h6>
                    <div class="heading-elements">

                    </div>
                </div>

                <div class="panel-body">

                    <div class="mb-20">
                        <a href="{{ url('/admin/dealers/auctioneer/'.$dealer->slug.'/event/create')}}">
                            <button class="btn bg-teal-400 btn-labeled" type="button">
                                <b>
                                    <i class="far fa-list-alt"></i>
                                </b>
                                Create New Event
                            </button>
                        </a></div>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="events-table">
                        <thead>
                        <tr>
                            <th class="">Event</th>
                            <th class="">Start Day</th>
                            <th class="">Start Time</th>
                            <th class="">End Time</th>
                            <th class="">Repeats</th>
                            <th class="">Updated</th>
                            <th class=""></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($dealer->calendarEvents as $event)
                            <tr>
                                <td>{{$event->title}}</td>
                                <td>{{$event->start_day->toFormattedDateString()}}</td>
                                <td>{{$event->start_time}}</td>
                                <td>{{$event->end_time}}</td>
                                <td>
                                    @if(!is_null($event->repeat_year))
                                        <i class="far fa-check"></i>
                                    @endif
                                </td>
                                <td>{{$event->updated_at->diffForHumans()}}</td>
                                <td><a href="{{ url('admin/dealers/auctioneer/'.$dealer->slug.'/event/'.$event->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-calendar-alt"></i></b> Details</a>
                                    <a href="{{ url('admin/dealers/auctioneer/'.$dealer->slug.'/event/'.$event->id.'/clone')}}" class="btn bg-info btn-labeled ml-10" type="button"><b><i class="far fa-clone"></i></b> Clone</a></td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>


                </div>
            </div>

        </div>

    </div>



@stop


