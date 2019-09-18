@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Gauk Settings' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Gauk Settings</span> - Manage Gauk Specific settings
@stop


@section('content')
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/gauk-settings/', 'class' => 'form-horizontal', 'autocomplete' => 'off')) !!}

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Import Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('gauk_import_api_key', 'Gauk Import API Key:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('gauk_import_api_key', Settings::get('gauk_import_api_key'), array('class' => 'form-control', 'id' => 'gauk_import_api_key', 'required')) !!}
                            {!!inputError($errors, 'gauk_import_api_key')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('gauk_import_api_status', 'Import Mode:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('gauk_import_api_status', array('live' => 'Live', 'test' => 'Sandbox'),Settings::get('gauk_import_api_status'), array('class' => 'form-control', 'id' => 'gauk_import_api_status')) !!}
                            {!!inputError($errors, 'gauk_import_api_status')!!}
                        </div>
                    </div>


                </div>
            </div>


        </div>

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Google Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('google_map_api_key', 'Google Maps API Key:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('google_map_api_key', Settings::get('google_map_api_key'), array('class' => 'form-control', 'id' => 'google_map_api_key', 'required')) !!}
                            {!!inputError($errors, 'google_map_api_key')!!}
                        </div>
                    </div>


                </div>
            </div>


        </div>

    </div>
    <div class="row">

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Content Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('main_content_role', 'Main Content Role:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('main_content_role', $roles, Settings::get('main_content_role'), ['class' => 'form-control', 'id' => 'main_content_role', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'main_content_role')!!}
                            <span class="help-block" for="route">Select the role which is required to view all auctioneer / vehicle content.</span>
                        </div>
                    </div>


                </div>
            </div>


        </div>

    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Commit Settings</h6>
                </div>

                <div class="panel-body">
                    {!! Form::button('<i class="far fa-save"></i> Save Settings', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                </div>
            </div>

        </div>
    </div>

    {!! Form::close() !!}
@stop


