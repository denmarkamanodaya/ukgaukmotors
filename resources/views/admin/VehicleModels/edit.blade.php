@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => '/admin/vehicle-makes', $vehicleModel->make->name => '/admin/vehicle-makes/'.$vehicleModel->make->slug, 'Edit Model' => 'is_current')) !!}

@stop

@section('page-header')
    <span class="text-semibold">Vehicle Model</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Vehicle Model Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    {!! Form::model($vehicleModel, array('method' => 'POST', 'url' => '/admin/vehicle-model/'.$vehicleModel->id.'/edit', 'class' => 'form-horizontal', 'id' => 'vehicle-model-edit')) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Vehicle Model:', ['class' => 'control-label col-lg-3']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                            {!!inputError($errors, 'name')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-5">
                            {!! Form::button('<i class="far fa-save"></i> Update Vehicle Model', array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                            {!! Form::close() !!}
                        </div>

                    </div>


                </div>
            </div>

        </div>


    </div>
@stop


