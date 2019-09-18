@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Users' => '/admin/users', 'Create' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold"> Create a new user</span>
    @stop


    @section('content')
    <div class="row">
        <div class="col-md-12">

                    <div class="" id="settings">

                        <!-- Profile info -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Profile information</h6>
                                <div class="heading-elements">
                                </div>
                            </div>

                            <div class="panel-body">
                                {!! Form::open(array('method' => 'POST', 'url' => '/admin/user/create', 'files' => true)) !!}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
                                            {!! Form::text('first_name', null, array('class' => 'form-control', 'required', 'placeholder' => 'First Name')) !!}
                                            @if ($errors->has('first_name'))
                                                <span class="help-block validation-error-label" for="first_name">{{ $errors->first('first_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
                                            {!! Form::text('last_name', null, array('class' => 'form-control', 'required', 'placeholder' => 'Last Name')) !!}
                                            @if ($errors->has('last_name'))
                                                <span class="help-block validation-error-label" for="first_name">{{ $errors->first('last_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
                                            {!! Form::text('address', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('address'))
                                                <span class="help-block validation-error-label" for="address">{{ $errors->first('address') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('address2', 'Address 2', array('class' => 'control-label')) !!}
                                            {!! Form::text('address2', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('address2'))
                                                <span class="help-block validation-error-label" for="address2">{{ $errors->first('address2') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
                                            {!! Form::text('city', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('city'))
                                                <span class="help-block validation-error-label" for="city">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('county', 'County', array('class' => 'control-label')) !!}
                                            {!! Form::text('county', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('county'))
                                                <span class="help-block validation-error-label" for="county">{{ $errors->first('county') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('postcode', 'Postcode', array('class' => 'control-label')) !!}
                                            {!! Form::text('postcode', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('postcode'))
                                                <span class="help-block validation-error-label" for="postcode">{{ $errors->first('postcode') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('email', 'Email Address', array('class' => 'control-label')) !!}
                                            {!! Form::email('email', null, array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('email'))
                                                <span class="help-block validation-error-label" for="email">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                                {!! Form::label('country_id', 'Country:', array('class' => 'control-label')) !!}
                                                {!! Form::select('country_id', $countries, 826, array('class' => 'form-control', 'id' => 'country_id')) !!}
                                                {!!inputError($errors, 'country_id')!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('telephone', 'Telephone', array('class' => 'control-label')) !!}
                                            {!! Form::text('telephone', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('telephone'))
                                                <span class="help-block validation-error-label" for="telephone">{{ $errors->first('telephone') }}</span>
                                            @endif
                                            <span class="help-block">+99-99-9999-9999</span>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('profilePic', 'Profile Picture', ['class' => 'control-label']) !!}
                                            {!! Form::file('profilePic') !!}
                                            @if ($errors->has('profilePic'))
                                                <script>formErrors.push("profilePic");</script>
                                                <span class="help-block validation-error-label" for="profilePic">{!! $errors->first('profilePic') !!}</span>
                                            @endif
                                            <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('bio', 'Bio', ['class' => 'control-label']) !!}
                                            {!! Form::textarea('bio', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('bio'))
                                                <script>formErrors.push("bio");</script>
                                                <span class="help-block validation-error-label" for="bio">{!! $errors->first('bio') !!}</span>
                                            @endif
                                            <span class="help-block">Bio is show on places like the blog post author details.</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /profile info -->

                    @if(count($notifications) > 0)
                        <!-- Notification settings -->
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Notification settings</h6>
                                    <div class="heading-elements">

                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">

                                                @foreach($notifications as $notification)
                                                    {!! Form::label('notification', $notification->name, array('class' => 'control-label')) !!}
                                                    {!! Form::text('notification['.$notification->slug.']', null, array('class' => 'form-control')) !!}
                                                    @if ($errors->has('notification.'.$notification->slug))
                                                        <span class="help-block validation-error-label" for="notification">{{ $errors->first('notification.'.$notification->slug) }}</span>
                                                    @endif
                                                    <span class="help-block">{!! $notification->description !!}</span>
                                                @endforeach


                                            </div>

                                            <div class="col-md-6">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Notification settings -->
                    @endif


                        <!-- Account settings -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Account settings</h6>
                                <div class="heading-elements">
                                </div>
                            </div>

                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('username', 'Username', array('class' => 'control-label')) !!}
                                            {!! Form::text('username', null, array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('username'))
                                                <span class="help-block validation-error-label" for="username">{{ $errors->first('username') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('password', 'Password', array('class' => 'control-label')) !!}
                                            {!! Form::password('password', array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('password'))
                                                <span class="help-block validation-error-label" for="password">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('password_confirmation', 'Repeat Password', array('class' => 'control-label')) !!}
                                            {!! Form::password('password_confirmation', array('class' => 'form-control', 'required')) !!}
                                            @if ($errors->has('password_confirmation'))
                                                <span class="help-block validation-error-label" for="password_confirmation">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('status', 'Status:', array('class' => 'control-label', 'required')) !!}<br>

                                            <label>
                                                <input type="radio" name="status" value="active">
                                                Active
                                            </label><br>
                                            <label>
                                                <input type="radio" name="status" value="banned">
                                                Banned
                                            </label><br>
                                            <label>
                                                <input type="radio" name="status" value="inactive">
                                                Inactive
                                            </label>
                                            @if ($errors->has('status'))
                                                <span class="help-block validation-error-label" for="status">{{ $errors->first('status') }}</span>
                                            @endif
                                            <br>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('memberships', 'Available Memberships', array('class' => 'control-label')) !!}
                                                {!! Form::select('memberships', $memberships,null, array('class' => 'form-control')) !!}
                                                {!!inputError($errors, 'memberships')!!}
                                                <span class="help-block">Add a membership to the user.</span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Roles</label>
                                            @foreach($roles as $role)
                                                <div class="checkbox">
                                                    <label>
                                                        {!! Form::checkbox('roles[]', $role->id, null, array('class' => 'styled')) !!} {!! $role->title !!}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @if ($errors->has('roles'))
                                                <span class="help-block validation-error-label" for="roles">{{ $errors->first('roles') }}</span>
                                            @endif
                                            <span class="help-block">Add a role to the user. Member is added as default.<br>Note : Membership role changes will override this selection.</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Notification</label>
                                                <div class="checkbox">
                                                    <label>
                                                        {!! Form::checkbox('send_welcome_email', '1', null, array('class' => 'styled')) !!} Send Welcome Email
                                                    </label>
                                                </div>
                                            @if ($errors->has('send_welcome_email'))
                                                <span class="help-block validation-error-label" for="send_welcome_email">{{ $errors->first('send_welcome_email') }}</span>
                                            @endif
                                            <span class="help-block">Note if adding a Membership an email could be sent through that. Only check this if you are sure an email is not going to be sent.</span>
                                        </div>

                                        <div class="col-md-6">

                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
                                </div>
                                </form>
                            </div>
                        </div>
                        <!-- /account settings -->

                    </div>

        </div>

    </div>
@stop


