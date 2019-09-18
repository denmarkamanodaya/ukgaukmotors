@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/ckeditor/ckeditor.js')}}"></script>
   <script type="text/javascript" src="{{ url('assets/js/page.js')}}"></script>
   <script type="text/javascript" src="{{ url('assets/js/pageCreate.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => '/admin/vehicle-makes', $vehicleMake->name => '/admin/vehicle-models/'.$vehicleMake->slug, 'Create Model' => 'is_current')) !!}

@stop

@section('page-header')
    <span class="text-semibold">Vehicle Model</span>
@stop


@section('content')
    @include('admin.VehicleModels.partials.wikiScrapeCreate')
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Vehicle Model Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/vehicle-models/'.$vehicleMake->slug.'/create', 'files' => true, 'id' => 'DescriptionForm', 'autocomplete' => 'off')) !!}

                    <div class="row mb-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('name', 'Vehicle Model Name:', ['class' => 'control-label col-lg-3']) !!}
                                <div class="col-lg-9">
                                    {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name', 'required')) !!}
                                    {!!inputError($errors, 'name')!!}
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('content', 'Description', array('class' => 'control-label', 'required')) !!}
                                <br>{!! Shortcode::showButton() !!}<br><br>
                                {!! Form::textarea('content', $description, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'content')) !!}
                                @if ($errors->has('content'))
                                    <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('featured_image', 'Featured Image', array('class' => 'control-label')) !!}
                                {!! Form::file('featured_image', array('class' => 'form-control')) !!}
                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                @if ($errors->has('featured_image'))
                                    <span class="help-block" for="featured_image">{{ $errors->first('featured_image') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary">Create Vehicle Model<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>
                    {!! Form::close() !!}

                </div>
            </div>

        </div>


    </div>
@stop


