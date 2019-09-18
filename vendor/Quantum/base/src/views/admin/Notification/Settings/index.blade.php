@extends('base::admin.Template')


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
    <span class="text-semibold">Notification Settings</span> - Manage notification settings
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Information</h6>
                </div>

                <div class="panel-body">
                    Through out the site certain user actions will trigger a configurable notification alert, these are managed here.<br>Below you will be able to set notification destinations and then subscribe to the available events.
                </div>
            </div>
        </div>
    </div>
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/notifications/update/', 'class' => 'form-horizontal')) !!}
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
                        {!! Form::label('site_notification_email', 'Site Notification Email:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::email('site_notification_email', Settings::get('site_notification_email'), array('class' => 'form-control', 'site_notification_email' => 'url', 'required', 'placeholder' => 'Site Email Address')) !!}
                            {!!inputError($errors, 'site_notification_email')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('site_notification_pushbullet_api', 'Site Notification Pushbullet:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('site_notification_pushbullet_api', Settings::get('site_notification_pushbullet_api'), array('class' => 'form-control', 'id' => 'site_notification_pushbullet_api', 'placeholder' => 'Pushbullet API Key')) !!}
                            {!!inputError($errors, 'site_notification_pushbullet_api')!!}
                            <span class="help-block" for="name">API Key can be generated in your Pushbullet members area. Accounts can be created <a href="https://www.pushbullet.com/" target="_blank">HERE</a></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('site_notification_pushover_token', 'Site Notification Pushover token:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('site_notification_pushover_token', Settings::get('site_notification_pushover_token'), array('class' => 'form-control', 'id' => 'site_notification_pushover_token', 'placeholder' => 'Pushover Token')) !!}
                            {!!inputError($errors, 'site_notification_pushover_token')!!}
                            <span class="help-block" for="name">Application Token Key must be generated in your Pushover members area under (Register an Application/Create an API Token). Accounts can be created <a href="https://pushover.net/" target="_blank">HERE</a></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('site_notification_pushover_user', 'Site Notification Pushover user:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('site_notification_pushover_user', Settings::get('site_notification_pushover_user'), array('class' => 'form-control', 'id' => 'site_notification_pushover_user', 'placeholder' => 'Pushover User Key')) !!}
                            {!!inputError($errors, 'site_notification_pushover_user')!!}
                            <span class="help-block" for="name">User Key can be found in your Pushover members area. Accounts can be created <a href="https://pushover.net/" target="_blank">HERE</a></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">User Notifications</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <h6>Select Notification</h6>
                    <p>Decide which notification types users are allowed to use for their notifications. Note: they will use their own settings not the ones opposite.</p>
                    @foreach($Notif_types as $Notif_type)
                        <div class="checkbox">
                            <label>
                                <div class=""><span class="">{!! Form::checkbox('allow_members[]', $Notif_type->id, $Notif_type->allow_members ? true : false, array('class' => 'styled', 'multiple')) !!}</span></div>
                                {{$Notif_type->name}}
                            </label>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>

    <div class="row">

        @foreach($Notif_events as $Notif_event)
            <div class="col-md-4">

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h6 class="panel-title">{{ $Notif_event->title }}</h6>
                    </div>

                    <div class="panel-body">
                        <h6>Notification Description</h6>
                        {{ $Notif_event->description }}
                        <hr>
                        <h6>Select Notification</h6>
                        @foreach($Notif_types as $Notif_type)
                            <div class="checkbox">
                                <label>
                                    <div class=""><span class="">{!! Form::checkbox($Notif_event->event.'_type[]', $Notif_type->id, in_array($Notif_type->id, $Notif_event->types->pluck('id')->toArray()) ? true : false, array('class' => 'styled', 'multiple')) !!}</span></div>
                                    {{$Notif_type->name}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endforeach
    </div>
    {!! Form::button('<i class="far fa-save"></i> Save Settings', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
    {!! Form::close() !!}
@stop


