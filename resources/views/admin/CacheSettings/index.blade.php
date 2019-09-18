@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Cache Settings' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Cache Settings</span> - Manage the cache
@stop


@section('content')

    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">General Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <p>Hit the below button to manually clear the cache.</p>
                    <a href="{!! url('admin/cache/settings/clear') !!}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-trash-alt"></i></b> Clear All Cached Items</a>


                </div>
            </div>


        </div>

    </div>


@stop


