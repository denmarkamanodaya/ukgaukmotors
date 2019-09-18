@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Membership' => '/admin/membership','Registration Form' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Registration Settings</span> - Manage the registration form
@stop


@section('content')
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/membership/settings/registration-form', 'class' => 'form-horizontal', 'autocomplete' => 'off')) !!}

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Form Fields</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <div class="row mb-20">
                        <div class="col-md-12">
                            Select which fields to show on the registration form.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_first_name" value="1" id="register_first_name" @if( Settings::get('register_first_name') == 1) checked='checked' @endif>
                                    First Name
                                    {!!inputError($errors, 'register_first_name')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_last_name" value="1" id="register_last_name" @if( Settings::get('register_last_name') == 1) checked='checked' @endif>
                                    Last Name
                                    {!!inputError($errors, 'register_last_name')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_address" value="1" id="register_address" @if( Settings::get('register_address') == 1) checked='checked' @endif>
                                    Address
                                    {!!inputError($errors, 'register_address')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_address2" value="1" id="register_address2" @if( Settings::get('register_address2') == 1) checked='checked' @endif>
                                    Address 2
                                    {!!inputError($errors, 'register_address2')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_city" value="1" id="register_city" @if( Settings::get('register_city') == 1) checked='checked' @endif>
                                    City
                                    {!!inputError($errors, 'register_city')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_county" value="1" id="register_county" @if( Settings::get('register_county') == 1) checked='checked' @endif>
                                    County
                                    {!!inputError($errors, 'register_county')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_postcode" value="1" id="register_postcode" @if( Settings::get('register_postcode') == 1) checked='checked' @endif>
                                    Postcode
                                    {!!inputError($errors, 'register_postcode')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="register_country" value="1" id="register_country" @if( Settings::get('register_country') == 1) checked='checked' @endif>
                                    Country
                                    {!!inputError($errors, 'register_country')!!}
                                </label>
                            </div>

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


