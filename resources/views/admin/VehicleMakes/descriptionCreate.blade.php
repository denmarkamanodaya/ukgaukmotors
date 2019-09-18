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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => '/admin/vehicle-makes', $vehicleMake->name => '/admin/vehicle-models/'.$vehicleMake->slug, 'Description' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Make Description</span>
@stop


@section('content')
@include('admin.VehicleMakes.partials.wikiScrape')
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicle Make : {{ $vehicleMake->name }}</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/vehicle-make/'.$vehicleMake->slug.'/description', 'files' => true, 'id' => 'DescriptionForm')) !!}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('content', 'Main Content', array('class' => 'control-label', 'required')) !!}
                                <br>{!! Shortcode::showButton() !!}<br><br>
                                {!! Form::textarea('content', $vehicleMake->description, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'content')) !!}
                                @if ($errors->has('content'))
                                    <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('featured_image', 'Featured Image', array('class' => 'control-label')) !!}
                                {!! Form::file('featured_image', '', array('class' => 'form-control')) !!}
                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                @if ($errors->has('featured_image'))
                                    <span class="help-block" for="featured_image">{{ $errors->first('featured_image') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary">Save Description<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>

        </div>

    </div>
    {!! Shortcode::showModal() !!}
@stop


