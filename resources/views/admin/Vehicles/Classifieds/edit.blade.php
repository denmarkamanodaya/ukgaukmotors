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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Classified Vehicles' => '/admin/vehicles/classifieds', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Classified Vehicle</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit Classified Vehicle</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::model($vehicle, array('method' => 'POST', 'url' => '/admin/vehicle/'.$vehicle->slug.'/classifiedEdit/update', 'files' => true, 'id' => 'ClassifiedForm', 'autocomplete' => 'off')) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicleType', 'Vehicle Type', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicleType', $vehicleTypes, $vehicle->vehicle_type_id, ['class' => 'form-control', 'id' => 'vehicleType', 'autocomplete' => 'false', 'data-placeholder' => 'Select Vehicle Type', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicleType')!!}
                            </div>
                        </div>
                    </div>

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
                                {!! Form::select('vehicleMake', $vehicleMakes, $vehicle->make->slug, ['class' => 'form-control', 'id' => 'vehicleMake', 'autocomplete' => 'false', 'data-placeholder' => 'Select Make', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicleMake')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicleModel', 'Vehicle Model', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicleModel', $vehicleModels, $vehicle->model->id, ['class' => 'form-control', 'id' => 'vehicleModel', 'autocomplete' => 'false', 'data-placeholder' => 'Select Model', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicleModel')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicleVarient', 'Vehicle Varient', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicleVarient', $vehicleVarients, $vehicle->vehicle_variant_id, ['class' => 'form-control', 'id' => 'vehicleVarient', 'autocomplete' => 'false', 'data-placeholder' => 'Select Model', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicleVarient')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('description', 'Description', array('class' => 'control-label')) !!}
                                {!! Form::textarea('description', null, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'description')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-12">
                            {!! Form::label('price', 'Price', array('class' => 'control-label')) !!}
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                {!! Form::number('price', null, array('class' => 'form-control touchspin-prefix', 'required', 'placeholder' => '150.00', 'step' => '0.01')) !!}
                            </div>
                            {!!inputError($errors, 'price')!!}
                        </div>
                    </div>


                    @foreach($features as $feature)
                        <div class="row">
                            <div class="col-md-12">
                                <h6>{!! hasIcon($feature)!!} {{$feature->name}}</h6>
                                    @foreach($feature->items as $item)
                                        <div class="col-md-3">
                                            {!! Form::checkbox('features[]', $item->id, hasFeature($item->id, $vehicle->features), array('class' => 'styled')) !!} {!! $item->name !!}
                                        </div>
                                    @endforeach
                            </div>
                        </div>
                    @endforeach



                    <div class="row  mt-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicle_body_type_id', 'Body Type', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicle_body_type_id', $vehicleMetaList['bodyType'], null, ['class' => 'form-control', 'id' => 'vehicle_body_type_id', 'autocomplete' => 'false', 'data-placeholder' => 'Select Engine Size', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicle_body_type_id')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('colour', 'Colour', array('class' => 'control-label')) !!}
                                {!! Form::text('colour', null, array('class' => 'form-control', 'required')) !!}
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
                                {!! Form::select('gearbox', ['automatic' => 'Automatic', 'manual' => 'Manual', 'semi-automatic' => 'Semi Automatic'], null, ['class' => 'form-control', 'id' => 'gearbox', 'autocomplete' => 'false', 'data-placeholder' => 'Select Gearbox', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'gearbox')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('fuel', 'Fuel', array('class' => 'control-label')) !!}
                                {!! Form::select('fuel', ['diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid', 'petrol' => 'Petrol'], null, ['class' => 'form-control', 'id' => 'fuel', 'autocomplete' => 'false', 'data-placeholder' => 'Select Fuel', 'tabindex' => '-1']) !!}

                                {!!inputError($errors, 'fuel')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('vehicle_engine_size_id', 'Engine Size', array('class' => 'control-label')) !!}
                                {!! Form::select('vehicle_engine_size_id', $vehicleMetaList['engineSize'], null, ['class' => 'form-control', 'id' => 'vehicle_engine_size_id', 'autocomplete' => 'false', 'data-placeholder' => 'Select Engine Size', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'vehicle_engine_size_id')!!}
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
                                {!! Form::label('service_history', 'Service History', array('class' => 'control-label')) !!}
                                {!! Form::select('service_history', serviceHistoryArray(), null, ['class' => 'form-control', 'id' => 'service_history', 'autocomplete' => 'false', 'data-placeholder' => 'Select Service History', 'tabindex' => '-1']) !!}
                                {!!inputError($errors, 'service_history')!!}
                            </div>
                        </div>
                    </div>

                    <h4>Vehicle Contact Details</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
                                {!! Form::text('address', $vehicle->location->address, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'address')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('address2', 'Address 2', array('class' => 'control-label')) !!}
                                {!! Form::text('address2', $vehicle->location->address2, array('class' => 'form-control')) !!}
                                {!!inputError($errors, 'address2')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
                                {!! Form::text('city', $vehicle->location->city, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'city')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('county', 'County', array('class' => 'control-label')) !!}
                                {!! Form::text('county', $vehicle->location->county, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'county')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('postcode', 'Post Code', array('class' => 'control-label')) !!}
                                {!! Form::text('postcode', $vehicle->location->postcode, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'postcode')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('phone', 'Phone', array('class' => 'control-label')) !!}
                                {!! Form::text('phone', $vehicle->location->phone, array('class' => 'form-control')) !!}
                                {!!inputError($errors, 'phone')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('email', 'Email', array('class' => 'control-label')) !!}
                                {!! Form::email('email', $vehicle->location->email, array('class' => 'form-control', 'required')) !!}
                                {!!inputError($errors, 'email')!!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('website', 'Website', array('class' => 'control-label')) !!}
                                {!! Form::text('website', $vehicle->location->website, array('class' => 'form-control')) !!}
                                <span class="help-block">Link to any sales page</span>
                                {!!inputError($errors, 'website')!!}
                            </div>
                        </div>
                    </div>


                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Edit Classified Vehicle<i class="fas fa-car position-right"></i></button>
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
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(array('method' => 'POST', 'url' => '/admin/vehicle/'.$vehicle->slug.'/classifiedEdit/uploadImages', 'autocomplete' => 'false', 'class' => 'dropzone', 'id' => 'dropzoneupload')) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@stop


