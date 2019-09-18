@extends('admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Notification Settings' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Ticket Settings</span> - Manage ticket settings
@stop


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Information</h6>
                </div>

                <div class="panel-body">
                    Below you will be able to change all settings related to the support tickets.
                </div>
            </div>
        </div>
    </div>
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/ticket/settings', 'class' => 'form-horizontal')) !!}
    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Email Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('ticket_email_from_address', 'Ticket Email From Address:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::email('ticket_email_from_address', Settings::get('ticket_email_from_address'), array('class' => 'form-control', 'ticket_email_from_address' => 'url', 'required', 'placeholder' => 'Email From Address')) !!}
                            {!!inputError($errors, 'ticket_email_from_address')!!}
                        </div>
                    </div>


                </div>
            </div>

        </div>

        <div class="col-md-6">


        </div>
    </div>

    {!! Form::button('<i class="far fa-save"></i> Save Settings', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
    {!! Form::close() !!}
@stop


