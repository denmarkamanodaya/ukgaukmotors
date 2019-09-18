@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => '/admin/vehicle-makes', $vehicleMake->name => '/admin/vehicle-models/'.$vehicleMake->slug, $variant->vehiclemodel->name => '/admin/vehicle-model-variants/'.$variant->vehiclemodel->id, 'Variant Detail' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Variant Details</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicle Variant : {{ $vehicleMake->name }} {{ $variant->vehiclemodel->name }}</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <h3>{{ $variant->model_desc }}</h3>
                    <ul>
                    <li>Year Sold : {{ $variant->year_sold }}</li>
                    <li>Location : {{ $variant->location }}</li>
                    <li>Classification : {{ $variant->classification }}</li>
                    </ul>

                    <h4>Body Data</h4>
                    <ul>
                    <li>Body Type : {{ $variant->body_type }}</li>
                    <li>Number of Doors : {{ $variant->doors }}</li>
                    <li>Number of Seats : {{ $variant->seats }}</li>
                    <li>Engine Place : {{ $variant->engine_place }}</li>
                    <li>Drivetrain : {{ $variant->drivetrain }}</li>
                    </ul>

                    <h4>Engine Data</h4>
                    <ul>
                    <li>Cylinders : {{ $variant->cylinders }}</li>
                    <li>Displacement (cm&sup3;): {{ $variant->displacement }}</li>
                    <li>Power (ps) : {{ $variant->power_ps }}</li>
                    <li>Power (kw) : {{ $variant->power_kw }}</li>
                    <li>Power (rpm) : {{ $variant->power_rpm }}</li>
                    <li>Torque (Nm) : {{ $variant->torque_nm }}</li>
                    <li>Torque (rpm) : {{ $variant->torque_rpm }}</li>
                    <li>Bore X Stroke (mm) : {{ $variant->bore_stroke }}</li>
                    <li>Compression Ratio : {{ $variant->compression_ration }}</li>
                    <li>Valves per : {{ $variant->valves_cylinder }}</li>
                    <li>Crankshaft : {{ $variant->crankshaft }}</li>
                    <li>Fuel Injection : {{ $variant->fuel_injection }}</li>
                    <li>Supercharger : {{ $variant->supercharged }}</li>
                    <li>Catalytic : {{ $variant->catalytic }}</li>
                    <li>Manual : {{ $variant->manual }}</li>
                    <li>Automatic : {{ $variant->automatic }}</li>
                    </ul>

                    <h4>Drivetrain Data</h4>
                    <ul>
                    <li>Suspension Front : {{ $variant->suspension_front }}</li>
                    <li>Suspension Rear : {{ $variant->suspension_rear }}</li>
                    <li>Assisted Stearing : {{ $variant->assisted_steering }}</li>
                    <li>Brakes Front : {{ $variant->brakes_front }}</li>
                    <li>Brakes Rear : {{ $variant->brakes_rear }}</li>
                    <li>ABS : {{ $variant->abs }}</li>
                    <li>ESP :  {{ $variant->esp }}</li>
                    <li>Tire Size : {{ $variant->tire_size }}</li>
                    <li>Tire Size Rear (If different from front): {{ $variant->tire_size_rear }}</li>
                    </ul>

                    <h4>Body Data</h4>
                    <ul>
                    <li>Wheel Base (mm) : {{ $variant->wheel_base }}</li>
                    <li>Track Front (mm) : {{ $variant->track_front }}</li>
                    <li>Track Rear (mm) : {{ $variant->track_rear }}</li>
                    <li>Length (mm) : {{ $variant->length }}</li>
                    <li>Width (mm) : {{ $variant->width }}</li>
                    <li>Height (mm) : {{ $variant->height }}</li>
                    <li>Curb Weight (kg) : {{ $variant->curb_weight }}</li>
                    <li>Gross Weight (kg) : {{ $variant->gross_weight }}</li>
                    <li>Cargo Space (litres) : {{ $variant->cargo_space }}</li>
                    <li>Tow Weight (kg) : {{ $variant->tow_weight }}</li>
                    <li>Gas Tank (litres) : {{ $variant->gas_tank }}</li>
                    </ul>

                    <h4>Performance Data</h4>
                    <ul>
                    <li>0 - 100kmph (sec) : {{ $variant->zero_hundred }}</li>
                    <li>Max Speed (kmh) : {{ $variant->max_speed }}</li>
                    <li>Fuel Efficiency (l/100km): {{ $variant->fuel_eff }}</li>
                    <li>Engine Type : {{ $variant->engine_type }}</li>
                    <li>Fuel Type : {{ $variant->fuel_type }}</li>
                    <li>CO2 : {{ $variant->co2 }}</li>
                    </ul>


                </div>
            </div>

        </div>

    </div>
@stop


