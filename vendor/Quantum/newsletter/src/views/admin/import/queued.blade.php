@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', 'Import Subscribers' => '/admin/newsletter/import', 'Queued Imports' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Import Subscribers</span> - Manage Queued Imports
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Queue</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Newsletter</th>
                            <th>CSV File</th>
                            <th>Welcome</th>
                            <th>Responder</th>
                            <th>Processed</th>
                            <th>Error</th>
                            <th>Completed</th>
                            <th>Updated At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($queued as $queue)
                            <tr>
                                <td>{{$queue->id}}</td>
                                <td>{{$queue->newsletter->title}}</td>
                                <td>{{$queue->csvfile}}</td>
                                <td>@if($queue->send_welcome == 1)<i class="far fa-check"></i>@endif</td>
                                <td>@if($queue->start_responder == 1)<i class="far fa-check"></i>@endif</td>
                                <td>{{$queue->startAt}}</td>
                                <td>@if($queue->error == 1)<i class="far fa-check"></i>@endif</td>
                                <td>@if($queue->completed == 1)<i class="far fa-check"></i>@endif</td>
                                <td>{{$queue->updated_at->diffForHumans()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>

@stop


