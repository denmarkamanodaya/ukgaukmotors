@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')


@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Importing' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Import</span> - Manage the importing
@stop


@section('content')


    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Import Types</h6>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                <div class="row mb-10">
                    <div class="col-md-6"><div class="mt-5">Import Features</div></div>
                    <div class="col-md-6"><a href="/admin/import/features" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-share"></i></b> Features</a></div>
                </div>

                    <div class="row mb-10">
                        <div class="col-md-6"><div class="mt-5">Import Categories</div></div>
                        <div class="col-md-6"><a href="/admin/import/categories" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-share"></i></b> Categories</a></div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-md-6"><div class="mt-5">Import Updated Dealers</div></div>
                        <div class="col-md-6"><a href="/admin/import/dealers" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-share"></i></b> Dealers</a></div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-md-6"><div class="mt-5">Import Parsed Dealers</div></div>
                        <div class="col-md-6"><a href="/admin/import/parsedDealers" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-share"></i></b> Parsed Dealers</a></div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-md-6"><div class="mt-5">Import Lots</div></div>
                        <div class="col-md-6"><a href="/admin/import/getLots" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-share"></i></b> Lots</a></div>
                    </div>


                </div>
            </div>

        </div>

    </div>

@stop


