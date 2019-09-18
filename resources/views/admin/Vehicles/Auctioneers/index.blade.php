@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', $auctioneer->name => '/admin/dealers/auctioneer/'.$auctioneer->slug, 'Vehicles' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicles</span>
@stop


@section('content')

<div class="row mb-15">
    <div class="col-md-2">
        @if($auctioneer->logo)
                    <img src="{{ url('/images/dealers/'.$auctioneer->id.'/thumb150-'.$auctioneer->logo)}}">
        @endif
    </div>
    <div class="col-md-10">
        <h4>{{$auctioneer->name}}</h4>
    </div>
</div>


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicles</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-lg-1">Id</th>
                            <th class="col-lg-7">Title</th>
                            <th class="col-lg-2">Auction Date</th>
                            <th class="col-lg-1">Created At</th>
                            <th class="col-lg-1">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($vehicles as $vehicle)
                            <tr>
                                <td>{{$vehicle->id}}</td>
                                <td>{{$vehicle->name}}</td>
                                <td>{{$vehicle->auction_date->toFormattedDateString()}}</td>
                                <td>{{$vehicle->created_at->diffForHumans()}}</td>
                                <td><a href="{{ url('admin/dealers/auctioneer/'.$auctioneer->slug.'/vehicle/'.$vehicle->slug)}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="fas fa-car"></i></b> Details</a></td>
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


