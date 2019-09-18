@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'General Settings' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Commerce Settings</span> - Manage payment settings
@stop


@section('content')
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/commerce/settings/', 'class' => 'form-horizontal', 'autocomplete' => 'off')) !!}

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Paypal Rest Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('PaypalRest_ClientId', 'Client ID:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('PaypalRest_ClientId', Settings::get('PaypalRest_ClientId'), array('class' => 'form-control', 'id' => 'PaypalRest_ClientId', 'required')) !!}
                            {!!inputError($errors, 'PaypalRest_ClientId')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('PaypalRest_ClientSecret', 'Client Secret:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('PaypalRest_ClientSecret', Settings::get('PaypalRest_ClientSecret'), array('class' => 'form-control', 'id' => 'PaypalRest_ClientSecret', 'required')) !!}
                            {!!inputError($errors, 'PaypalRest_ClientSecret')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('PaypalRest_mode', 'Paypal Mode:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('PaypalRest_mode', array('live' => 'Live', 'sandbox' => 'Sandbox'),Settings::get('PaypalRest_mode'), array('class' => 'form-control', 'id' => 'PaypalRest_mode')) !!}
                            {!!inputError($errors, 'PaypalRest_mode')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('PaypalRest_Hook_Id', 'Webhook ID:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('PaypalRest_Hook_Id', Settings::get('PaypalRest_Hook_Id'), array('class' => 'form-control', 'id' => 'PaypalRest_ipn_passthrough', 'required')) !!}
                            {!!inputError($errors, 'PaypalRest_Hook_Id')!!}
                            <span class="help-block">The webhook id is found within your paypal account once a webhook has been created</span>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('PaypalRest_ipn_passthrough', 'Ipn Passthrough url\'s:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('PaypalRest_ipn_passthrough', Settings::get('PaypalRest_ipn_passthrough'), array('class' => 'form-control', 'id' => 'PaypalRest_ipn_passthrough')) !!}
                            {!!inputError($errors, 'PaypalRest_ipn_passthrough')!!}
                            <span class="help-block">Send the ipn to other listeners. Separate multiple Url's with a comma</span>
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


