@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/dropzone.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/addVehicle.js')}}"></script>

    <script>
        function loadVehicleImages() {
            var $container = $('#vehicleImages');
            $.ajax({
                type: "GET",
                url: baseUrl + '/admin/vehicle/{{$vehicle->slug}}/classifiedEdit/loadImages',
                success: function (data) {
                    $container.html(data);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
            return true;
        }

        Dropzone.options.dropzoneupload = {
            paramName: 'image',
            maxFilesize: 2,
            acceptedFiles: 'image/*',
            init: function () {
                this.on("complete", function (file) {
                    loadVehicleImages();
                });
            }
        };
        loadVehicleImages();
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    @if($previous == '/admin/vehicleTools/unclassified/matchLogs')
        {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'VehiclesTools' => 'is_current', 'Unclassified' => '/admin/vehicleTools/unclassified', 'Matched Vehicle Logs' => '/admin/vehicleTools/unclassified/matchLogs', 'Edit' => 'is_current')) !!}
    @else
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auction Vehicles' => '/admin/vehicles', 'Edit' => 'is_current')) !!}
    @endif
@stop

@section('page-header')
    <span class="text-semibold">Auction Vehicle</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit Auction Vehicle</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::model($vehicle, array('method' => 'POST', 'url' => '/admin/vehicle/'.$vehicle->slug.'/auctionEdit/update', 'id' => 'ClassifiedForm', 'autocomplete' => 'off')) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
                                {!! Form::text('title', $vehicle->name, array('class' => 'form-control', 'required')) !!}
                                <span class="help-block">The adverts descriptive title.</span>
                                {!!inputError($errors, 'name')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicleMake', 'Vehicle Make', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicleMake', $vehicleMakes, isset($vehicle->make->slug) ? $vehicle->make->slug : null, ['class' => 'form-control', 'id' => 'vehicleMake', 'autocomplete' => 'false', 'data-placeholder' => 'Select Make', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicleMake')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicleModel', 'Vehicle Model', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicleModel', $vehicleModels, isset($vehicle->model->id) ? $vehicle->model->id : null, ['class' => 'form-control', 'id' => 'vehicleModel', 'autocomplete' => 'false', 'data-placeholder' => 'Select Model', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicleModel')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicleVarient', 'Vehicle Varient', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicleVarient', $vehicleVarients, isset($vehicle->vehicle_variant_id) ? $vehicle->vehicle_variant_id : null, ['class' => 'form-control', 'id' => 'vehicleVarient', 'autocomplete' => 'false', 'data-placeholder' => 'Select Model', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicleVarient')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('description', 'Description', array('class' => 'control-label')) !!}
                                {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
                                {!!inputError($errors, 'description')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-12">
                            {!! Form::label('price', 'Price', array('class' => 'control-label')) !!}
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                {!! Form::number('price', null, array('class' => 'form-control touchspin-prefix', 'placeholder' => '', 'step' => '0.01')) !!}
                            </div>
                            {!!inputError($errors, 'price')!!}
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('colour', 'Colour', array('class' => 'control-label')) !!}
                                {!! Form::text('colour', null, array('class' => 'form-control')) !!}
                                {!!inputError($errors, 'colour')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('mileage', 'Mileage', array('class' => 'control-label')) !!}
                                {!! Form::number('mileage', null, array('class' => 'form-control')) !!}
                                {!!inputError($errors, 'mileage')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('gearbox', 'Gearbox', array('class' => 'control-label')) !!}
                                {!! Form::select('gearbox', ['unlisted' => 'Unlisted', 'automatic' => 'Automatic', 'manual' => 'Manual', 'semi-automatic' => 'Semi Automatic'], null, ['class' => 'form-control', 'id' => 'gearbox', 'autocomplete' => 'false', 'data-placeholder' => 'Select Gearbox', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'gearbox')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('fuel', 'Fuel', array('class' => 'control-label')) !!}
                                {!! Form::select('fuel', ['unlisted' => 'Unlisted', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid', 'petrol' => 'Petrol'], null, ['class' => 'form-control', 'id' => 'fuel', 'autocomplete' => 'false', 'data-placeholder' => 'Select Fuel', 'tabindex' => '-1']) !!}

                                {!!inputError($errors, 'fuel')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('registration', 'Registration Number', array('class' => 'control-label')) !!}
                                {!! Form::text('registration', null, array('class' => 'form-control')) !!}
                                {!!inputError($errors, 'registration')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('mot', 'MOT', array('class' => 'control-label')) !!}
                                {!! Form::date('mot', null, array('class' => 'form-control')) !!}
                                <span class="help-block">Do not change if vehicle has no MOT or date unknown.</span>
                                {!!inputError($errors, 'mot')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('website', 'Website', array('class' => 'control-label')) !!}
                                {!! Form::text('website', isset($vehicle->url) ? $vehicle->url : null, array('class' => 'form-control')) !!}
                                <span class="help-block">Link to any sales page</span>
                                {!!inputError($errors, 'website')!!}
                            </div>
                        </div>
                    </div>


                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Edit Auction Vehicle<i class="fas fa-car position-right"></i></button>
                    </div>

                    {!! Form::close() !!}



                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Vehicle Images</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12" id="vehicleImages">
                        </div>
                    </div>
                </div>
            </div>

        </div>

            <div class="col-md-4">
            @if($vehicle->dealer)


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h6 class="panel-title">{{ucfirst($vehicle->dealer->type)}} Info</h6>
                                <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>
                            <div class="panel-body text-center">
                                @if($vehicle->dealer->logo)
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-10">
                                            <img src="{{ url('/images/dealers/'.$vehicle->dealer->id.'/thumb150-'.$vehicle->dealer->logo)}}">
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-20">
                                    <div class="col-md-12">
                                        {{$vehicle->dealer->name}}
                                    </div>
                                </div>

                                <div class="row mb-20">
                                    <div class="col-md-6">
                                        <a href="{{ url('admin/auctioneer/'.$vehicle->dealer->slug.'/vehicles')}}" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-car"></i></b> View {{ucfirst($vehicle->dealer->type)}}s Vehicles</a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ url('admin/auctioneer/'.$vehicle->dealer->slug)}}" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-gavel"></i></b> View {{ucfirst($vehicle->dealer->type)}}</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Auction Date</h6>
                                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>
                                <div class="panel-body text-center">
                                    <div class="row mb-20">
                                        <div class="col-md-6 text-left text-bold">
                                            Removal Date :
                                        </div>
                                        <div class="col-md-6 text-left">
                                            {{$vehicle->auction_date->toDayDateTimeString()}}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                @endif
            </div>

    </div>

@stop


