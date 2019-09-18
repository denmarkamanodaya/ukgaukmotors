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
    <span class="text-semibold">Settings</span> - Manage general settings
@stop


@section('content')
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/settings/update/', 'class' => 'form-horizontal')) !!}

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Site Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('site_name', 'Site Name:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('site_name', Settings::get('site_name'), array('class' => 'form-control', 'id' => 'url', 'required')) !!}
                            {!!inputError($errors, 'site_name')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('use_gravatar', 'Use Gravatar:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('use_gravatar', ['yes' => 'Yes', 'no' => 'No'], Settings::get('use_gravatar'), ['class' => 'form-control', 'id' => 'use_gravatar', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'use_gravatar')!!}
                        </div>
                    </div>

                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Email Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('site_email_address', 'Site Email Address:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::email('site_email_address', Settings::get('site_email_address'), array('class' => 'form-control', 'id' => 'site_email_address', 'required')) !!}
                            {!!inputError($errors, 'site_email_address')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('site_email_from_name', 'Site Email From Name:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('site_email_from_name', Settings::get('site_email_from_name'), array('class' => 'form-control', 'id' => 'site_email_from_name', 'required')) !!}
                            {!!inputError($errors, 'site_email_from_name')!!}
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Page Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('members_home_page', 'Members Home Page:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('members_home_page', $memberPages, Settings::get('members_home_page'), ['class' => 'form-control', 'id' => 'members_home_page', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'members_home_page')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('members_home_page_title', 'Members Home Page Title:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('members_home_page_title', Settings::get('members_home_page_title'), array('class' => 'form-control', 'id' => 'members_home_page_title', 'required')) !!}
                            {!!inputError($errors, 'members_home_page_title')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('contact_thankyou_page', 'Contact Thank You Page:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('contact_thankyou_page', $pages, Settings::get('contact_thankyou_page'), ['class' => 'form-control', 'id' => 'contact_thankyou_page', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'contact_thankyou_page')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('members_checkout_page', 'Members Checkout Page:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('members_checkout_page', $memberPages, Settings::get('members_checkout_page'), array('class' => 'form-control', 'id' => 'members_checkout_page', 'required')) !!}
                            {!!inputError($errors, 'members_checkout_page')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('public_checkout_page', 'Public Checkout Page:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('public_checkout_page', $pages, Settings::get('public_checkout_page'), array('class' => 'form-control', 'id' => 'public_checkout_page', 'required')) !!}
                            {!!inputError($errors, 'public_checkout_page')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('members_upgrade_page', 'Members Upgrade Page:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('members_upgrade_page', $memberPages, Settings::get('members_upgrade_page'), array('class' => 'form-control', 'id' => 'members_upgrade_page', 'required')) !!}
                            {!!inputError($errors, 'members_upgrade_page')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('members_deleted_account_page', 'Members Account Deleted Page:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('members_deleted_account_page', $pages, Settings::get('members_deleted_account_page'), array('class' => 'form-control', 'id' => 'members_deleted_account_page', 'required')) !!}
                            {!!inputError($errors, 'members_deleted_account_page')!!}
                        </div>
                    </div>

                </div>
            </div>


        </div>

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Theme Settings</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('public_theme', 'Public Theme:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('public_theme', $themelist, Settings::get('public_theme'), ['class' => 'form-control', 'id' => 'public_theme', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'public_theme')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('members_theme', 'Members Theme:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('members_theme', $themelist, Settings::get('members_theme'), ['class' => 'form-control', 'id' => 'members_theme', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'members_theme')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('admin_theme', 'Admin Theme:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('admin_theme', $themelist, Settings::get('admin_theme'), ['class' => 'form-control', 'id' => 'admin_theme', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'admin_theme')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('email_theme', 'Email Theme:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('email_theme', $themelist, Settings::get('email_theme'), ['class' => 'form-control', 'id' => 'email_theme', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'email_theme')!!}
                        </div>
                    </div>


                </div>
            </div>

            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Site Country Settings</h6>
                        <div class="heading-elements">
                        </div>
                        <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                    <div class="panel-body">
                        <div class="form-group">
                            {!! Form::label('site_country', 'Site Country:', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::select('site_country', $countrylist, Settings::get('site_country'), ['class' => 'form-control', 'id' => 'site_country', 'autocomplete' => 'false']) !!}
                                {!!inputError($errors, 'site_country')!!}
                            </div>
                        </div>

                        @if($sitecountry->flag)
                            <div class="row">
                                <div class="col-lg-2"><img src="{{url('/images/flags/'.$sitecountry->flag)}}"></div>
                                <div class="col-lg-10"></div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-2">Country Code</div>
                            <div class="col-lg-10">{{$sitecountry->iso_3166_3}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">Currency</div>
                            <div class="col-lg-10">{{$sitecountry->currency}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">Currency Sub Unit</div>
                            <div class="col-lg-10">{{$sitecountry->currency_sub_unit}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">Currency Code</div>
                            <div class="col-lg-10">{{$sitecountry->currency_code}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">Currency Symbol</div>
                            <div class="col-lg-10">{{$sitecountry->currency_symbol}}</div>
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


