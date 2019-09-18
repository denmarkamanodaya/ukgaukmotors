@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Features' => '/admin/vehicle-features', 'Create Category' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Features</span> - Edit category
@stop


@section('content')


    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div id="accordion-control-right" class="panel-group panel-group-control panel-group-control-right content-group-lg">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">Category Details</a>
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">
                            {!! Form::model($feature, array('method' => 'POST', 'url' => '/admin/vehicle-features/'.$feature->slug.'/update', 'id' => 'FeatureEdit', 'autocomplete' => 'off')) !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('name', 'Category Name', array('class' => 'control-label')) !!}
                                        {!! Form::text('name', null, array('class' => 'form-control', 'required')) !!}
                                        {!!inputError($errors, 'name')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('icon', 'Icon:', ['class' => 'control-label col-lg-2']) !!}
                                        {!! Form::select('icon', $icons, null, ['class' => 'form-control icon-select']) !!}
                                        {!!inputError($errors, 'icon')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Edit Category<i class="far fa-save position-right"></i></button>
                                </div>

                            </div>
                            {!! Form::close() !!}


                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@stop


