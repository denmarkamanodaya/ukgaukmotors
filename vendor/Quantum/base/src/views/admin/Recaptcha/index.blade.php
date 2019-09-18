@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Recaptcha Settings' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Recaptcha Settings</span> - Manage settings
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Information</h6>
                </div>

                <div class="panel-body">
                    Show the google Recaptcha on selected forms.<br>
                    You can get your Recaptcha details from <a target="_blank" href="https://www.google.com/recaptcha/intro/">HERE</a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/recaptcha/settings/update/', 'class' => 'form-horizontal')) !!}
    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">General Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('recaptcha_site_key', 'Site Key:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('recaptcha_site_key', Settings::get('recaptcha_site_key'), array('class' => 'form-control', 'recaptcha_site_key' => 'url', 'required', 'placeholder' => 'Site Key')) !!}
                            {!!inputError($errors, 'recaptcha_site_key')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('recaptcha_secret_key', 'Secret Key:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('recaptcha_secret_key', Settings::get('recaptcha_secret_key'), array('class' => 'form-control', 'id' => 'recaptcha_secret_key', 'placeholder' => 'Secret Key')) !!}
                            {!!inputError($errors, 'recaptcha_secret_key')!!}
                        </div>
                    </div>



                </div>
            </div>

        </div>


        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Area Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <div class=""><span class="">{!! Form::checkbox('recaptcha_register', 1, Settings::get('recaptcha_register') ? true : false, array('class' => 'styled')) !!}</span></div>
                                Registration Form
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <div class=""><span class="">{!! Form::checkbox('recaptcha_login', 1, Settings::get('recaptcha_login') ? true : false, array('class' => 'styled')) !!}</span></div>
                                Login Form
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <div class=""><span class="">{!! Form::checkbox('recaptcha_password', 1, Settings::get('recaptcha_password') ? true : false, array('class' => 'styled')) !!}</span></div>
                                Password Forgot Forms
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <div class=""><span class="">{!! Form::checkbox('recaptcha_guest_ticket', 1, Settings::get('recaptcha_guest_ticket') ? true : false, array('class' => 'styled')) !!}</span></div>
                                Guest Support Ticket Forms
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <div class=""><span class="">{!! Form::checkbox('recaptcha_guest_newsletter', 1, Settings::get('recaptcha_guest_newsletter') ? true : false, array('class' => 'styled')) !!}</span></div>
                                Guest Newsletter Forms
                            </label>
                        </div>
                    </div>




                </div>
            </div>

        </div>


    </div>

    <div class="row">


    </div>
    {!! Form::button('<i class="far fa-save"></i> Save Settings', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
    {!! Form::close() !!}
@stop


