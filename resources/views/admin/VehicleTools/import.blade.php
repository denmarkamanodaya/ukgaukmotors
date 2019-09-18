@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'VehiclesTools' => 'is_current', 'Import' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Import Tools</span>
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Available Import Actions</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 mb-20">
                            The actions here are generally best left alone as they are run via cron but are made available for testing purposes.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <h4>Import Vehicles</h4>
                            <p>This will Manually run the vehicle importer.<br>This Can take a long time to run.</p>
                            <a href="{{ url('/admin/vehicleTools/import/importVehicles')}}">
                                <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                                    <b>
                                        <i class="fas fa-car"></i>
                                    </b>
                                    Import Vehicles
                                </button>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <h4>Process Images</h4>
                            <p>This will manually process any imported vehicle images.</p>
                            <a href="{{ url('/admin/vehicleTools/import/importImages')}}">
                                <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                                    <b>
                                        <i class="far fa-image"></i>
                                    </b>
                                    Import Images
                                </button>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <h4>Expire Auctions</h4>
                            <p>This will manually expire outdated vehicles.</p>
                            <a href="{{ url('/admin/vehicleTools/import/expire')}}">
                                <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                                    <b>
                                        <i class="far fa-clock"></i>
                                    </b>
                                    Expire Vehicles
                                </button>
                            </a>
                        </div>

                    </div>


                </div>
            </div>

        </div>

    </div>

@stop


