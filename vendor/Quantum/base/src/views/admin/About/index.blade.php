@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'About' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">About</span> - Script Information
@stop


@section('content')
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                Below you will find information about all the installed modules along with their current version numbers.
            </div>
        </div>
    </div>

    <div class="row">
    @foreach($modules as $module)

            <div class="col-md-4">

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h6 class="panel-title">{!! $module->name !!}</h6>
                        <div class="heading-elements">
                        </div>
                        <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                {!! $module->description !!}
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <span class="pull-right">Version : {!! $module->version !!}</span>
                    </div>
                </div>

            </div>

    @endforeach
    </div>

@stop


