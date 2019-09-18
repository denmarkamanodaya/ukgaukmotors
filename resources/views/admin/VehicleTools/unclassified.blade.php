@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'VehiclesTools' => 'is_current', 'Unclassified' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Unclassified Vehicles</span>
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicles</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <a href="{{ url('/admin/vehicleTools/unclassified/matchLogs')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b>
                                <i class="far fa-eye"></i>
                            </b>
                            View Matched Logs
                        </button>
                    </a>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-lg-1">Id</th>
                            <th class="col-lg-5">Title</th>
                            <th class="col-lg-2">Auction Date</th>
                            <th class="col-lg-1">Created At</th>
                            <th class="col-lg-1">Failed</th>
                            <th class="col-lg-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($vehicles as $vehicle)
                            <tr>
                                <td>{{$vehicle->id}}</td>
                                <td>{{$vehicle->name}}</td>
                                <td>{{$vehicle->auction_date->toFormattedDateString()}}</td>
                                <td>{{$vehicle->created_at->diffForHumans()}}</td>
                                <td>
                                    @if($vehicle->match_attempt == 1)
                                        <i class="far fa-times"></i>
                                    @elseif($vehicle->match_attempt == 2)
                                        <i class="far fa-times"></i> <i class="far fa-times"></i>
                                    @endif
                                </td>
                                <td><a href="{{ url('admin/vehicleTools/unclassified/findMatch/'.$vehicle->slug)}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-question"></i></b> Match Up</a>
                                    <a href="{{ url('admin/vehicle/'.$vehicle->slug.'/auctionEdit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="fas fa-car"></i></b> Details</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $vehicles->count() !!} out of {!! $vehicles->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $vehicles->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


