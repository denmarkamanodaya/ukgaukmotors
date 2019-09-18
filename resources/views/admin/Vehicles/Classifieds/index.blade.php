@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Classified Vehicles' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicles</span>
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Classified Vehicles</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{ url('/admin/vehicles/classifieds/create')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b>
                                <i class="fas fa-car"></i>
                            </b>
                            Add A New Classified Vehicle
                        </button>
                    </a>
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-lg-1"></th>
                            <th class="col-lg-6">Title</th>
                            <th class="col-lg-2">Expire Date</th>
                            <th class="col-lg-1">Updated At</th>
                            <th class="col-lg-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($vehicles as $vehicle)
                            <tr>
                                @if (isset($vehicle->images) && $vehicle->images !== '')
                                    @php
                                        $images = explode(", ", $vehicle->images);
                                    @endphp
                                    @if (count($images)) 
                                        <td><img src="{{ $images[0] }}" alt="" class="img-responsive"></td>
                                    @endif
                                @elseif (count($vehicle->media))
                                    <td>{!! showVehicleImage($vehicle->media->first(), true) !!}</td>
                                @else
                                    <td><img src="{!! url('/images/image-Not-available.jpg') !!}" alt=""></td>
                                @endif
                                <td>{{$vehicle->name}}</td>
                                <td>{{$vehicle->expire_date->toFormattedDateString()}}</td>
                                <td>{{$vehicle->updated_at->diffForHumans()}}</td>
                                <td><a href="{{ url('admin/vehicle/'.$vehicle->slug)}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="fas fa-car"></i></b> Details</a>
                                    <a href="{{ url('admin/vehicle/'.$vehicle->slug.'/classifiedEdit')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                </td>
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


