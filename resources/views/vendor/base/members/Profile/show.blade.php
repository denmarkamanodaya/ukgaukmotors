@extends('base::members.Template')

@section('body-class', 'cs-agent-detail single-page')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{ url('assets/js/plugins/datatables/datatables.min.js')}}"></script>
    <script type="text/javascript">
        $('#pictureDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to remove the image?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });

        $('#userDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete your account?<p>Please note this action can not be reversed. A new account will need to be created if you wish to regain access to the members area. </p>',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });

        $(function() {
            $('#activity-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('members/logs/activity') !!}',
                columns: [
                    {data: 'text', name: 'text'},
                    {data: 'created_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }}
                ],
                order: [[ 1, 'asc' ]]
            });
        });

        $(function() {
            $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('members/logs/payments') !!}',
                columns: [
                    {data: 'payment_gateway.userTitle', name: 'payment_gateway_title'},
                    {data: 'trx_id', name: 'trx_id'},
                    {data: 'amount', name: 'amount'},
                    {data: 'created_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }}
                ],
                order: [[ 3, 'desc' ]]
            });
        });

        $('a.subnews').click(function(event) {
            event.preventDefault();
            var btnId = $(this).attr('id');
            console.log(btnId);
            $.ajax({
                url: $(this).attr('href'),
                success: function(response) {
                    bootbox.alert(response.successHtml);
                    if(response.success === true)
                    {
                        $('#'+btnId).css('display','none');
                        $('#'+btnId).next('a').css('display','inline-block');
                    }
                }
            });
            return false; // for good measure
        });

        $('a.unsubnews').click(function(event) {
            event.preventDefault();
            var btnId = $(this).attr('id');
            console.log(btnId);
            $.ajax({
                url: $(this).attr('href'),
                success: function(response) {
                    bootbox.alert(response.content);
                    $('#'+btnId).css('display','none');
                    $('#'+btnId).prev('a').css('display','inline-block');
                }
            });
            return false; // for good measure
        });

        $('a.newsdetails').click(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                success: function(response) {
                    bootbox.alert({
                        title: response.title,
                        message: response.content
                    });
                }
            });
            return false; // for good measure
        });

    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Profile' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold"> Profile</span>
@stop


@section('content')

    <div class="navbar navbar-default navbar-component navbar-xs">
        <ul class="nav navbar-nav visible-xs-block">
            <li class="full-width text-center"><a data-target="#navbar-filter" data-toggle="collapse"><i class="icon-menu7"></i></a></li>
        </ul>

        <div id="navbar-filter" class="navbar-collapse collapse">
            <ul class="nav navbar-nav element-active-slate-400">
                <li class="active"><a data-toggle="tab" href="#profile" aria-expanded="false"><i class="far fa-user position-left"></i> Profile</a></li>
                <li class=""><a data-toggle="tab" href="#transactions" aria-expanded="true"><i class="far fa-money-bill-alt position-left"></i> Transactions</a></li>
                <li><a data-toggle="tab" href="#activity"><i class="far fa-cubes position-left"></i> Activity</a></li>
                <li><a data-toggle="tab" href="#newsletters"><i class="far fa-newspaper position-left"></i> Newsletters</a></li>

            </ul>

            <div class="navbar-right">

            </div>
        </div>
    </div>

    <!-- User profile -->
    <div class="row">
        <div class="col-lg-9">

            <div class="tabbable">
                <div class="tab-content">
                    <div id="profile" class="tab-pane fade active in">
                        <div class="" id="settings">

                            <!-- Profile info -->
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Profile information</h6>
                                    <div class="heading-elements">

                                    </div>
                                </div>

                                <div class="panel-body">
                                    {!! Form::model($user,array('method' => 'POST', 'url' => '/members/profile/update', 'files' => true)) !!}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
                                                {!! Form::text('first_name', $user->profile->first_name, array('class' => 'form-control', 'required')) !!}
                                                @if ($errors->has('first_name'))
                                                    <span class="help-block validation-error-label" for="first_name">{{ $errors->first('first_name') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
                                                {!! Form::text('last_name', $user->profile->last_name, array('class' => 'form-control', 'required')) !!}
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
                                                {!! Form::text('address', $user->profile->address, array('class' => 'form-control')) !!}
                                                @if ($errors->has('address'))
                                                    <span class="help-block validation-error-label" for="address">{{ $errors->first('address') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('address2', 'Address 2', array('class' => 'control-label')) !!}
                                                {!! Form::text('address2', $user->profile->address2, array('class' => 'form-control')) !!}
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
                                                {!! Form::text('city', $user->profile->city, array('class' => 'form-control')) !!}
                                                @if ($errors->has('city'))
                                                    <span class="help-block validation-error-label" for="city">{{ $errors->first('city') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::label('county', 'County', array('class' => 'control-label')) !!}
                                                {!! Form::text('county', $user->profile->county, array('class' => 'form-control')) !!}
                                                @if ($errors->has('county'))
                                                    <span class="help-block validation-error-label" for="county">{{ $errors->first('county') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::label('postcode', 'Postcode', array('class' => 'control-label')) !!}
                                                {!! Form::text('postcode', $user->profile->postcode, array('class' => 'form-control')) !!}
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
                                                {!! Form::email('email', $user->email, array('class' => 'form-control', 'required')) !!}
                                                @if ($errors->has('email'))
                                                    <span class="help-block validation-error-label" for="email">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('country_id', 'Country:', array('class' => 'control-label')) !!}
                                                {!! Form::select('country_id', $countries, $user->profile->country_id, array('class' => 'form-control', 'id' => 'country_id')) !!}
                                                {!!inputError($errors, 'country_id')!!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('telephone', 'Telephone', array('class' => 'control-label')) !!}
                                                {!! Form::text('telephone', $user->profile->telephone, array('class' => 'form-control')) !!}
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
                                                    <?php $user->load('notifications');?>
                                                    @foreach($notifications as $notification)
                                                        {!! Form::label('notification', $notification->name, array('class' => 'control-label')) !!}
                                                        {!! Form::text('notification['.$notification->slug.']', notifactionSetting($notification, $user->notifications), array('class' => 'form-control')) !!}
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
                                                <p class="form-control-static"><b>{!! $user->username !!}</b></p>
                                            </div>

                                            <div class="col-md-6">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('password', 'Password', array('class' => 'control-label')) !!}
                                                {!! Form::password('password', array('class' => 'form-control')) !!}
                                                @if ($errors->has('password'))
                                                    <span class="help-block validation-error-label" for="password">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('password_confirmation', 'Repeat Password', array('class' => 'control-label')) !!}
                                                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block validation-error-label" for="password_confirmation">{{ $errors->first('password_confirmation') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">

                                            </div>

                                            <div class="col-md-6">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="cs-field-holder">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="cs-btn-submit">
                                                <input type="submit" value="Save Profile Changes">
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /account settings -->

                        </div>
                    </div>
                    <div id="transactions" class="tab-pane fade">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Transactions</h6>
                                <div class="heading-elements">

                                </div>
                            </div>

                            <div class="panel-body">
                                <table class="table datatable-ajax table-bordered table-striped table-hover" id="transactions-table">
                                    <thead>
                                    <tr>
                                        <th class="col-lg-2">Payment Gateway</th>
                                        <th class="col-lg-3">Trans Id</th>
                                        <th class="col-lg-1">Amount</th>
                                        <th class="col-lg-2">When</th>
                                    </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="activity" class="tab-pane fade">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Activity - Past 60 days </h6>
                                <div class="heading-elements">

                                </div>
                            </div>

                            <div class="panel-body">
                                <table class="table datatable-ajax table-bordered table-striped table-hover" id="activity-table">
                                    <thead>
                                    <tr>
                                        <th class="col-lg-3">Action</th>
                                        <th class="col-lg-1">When</th>
                                    </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="newsletters" class="tab-pane fade">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Manage Newsletters </h6>
                                <div class="heading-elements">


                                </div>
                            </div>

                            <div class="panel-body">
                                @if($newsletters->count() > 0)

                                    <div class="row">
                                        @foreach($newsletters as $newsletter)
                                            <div class="col-sm-6 col-md-6">
                                                <div class="thumbnail newsletter-box">
                                                    @if($newsletter->featured_image)
                                                        <div class="thumb">
                                                            <img src="{!! $newsletter->featured_image !!}">
                                                        </div>
                                                    @endif
                                                    <div class="caption">

                                                        <h3>{{$newsletter->title}}</h3>
                                                        <p>{{$newsletter->summary}}</p>
                                                        <p>
                                                            <a href="{!! url('members/newsletter/details/'.$newsletter->slug) !!}" class="btn btn-primary newsdetails" role="button">Details</a>
                                                            @if(is_subscribed($newsletter, $newsSubscriptions))
                                                                <a style="display: none" id='sub_{{$newsletter->slug}}' href="{!! url('members/newsletter/subscribe/'.$newsletter->slug) !!}" class="btn btn-success subnews" role="button">Subscribe</a>
                                                                <a id='unsub_{{$newsletter->slug}}' href="{!! url('members/newsletter/unsubscribe/'.$newsletter->slug) !!}" class="btn btn-warning unsubnews" role="button">Unsubscribe</a>
                                                            @else
                                                                <a id='sub_{{$newsletter->slug}}' href="{!! url('members/newsletter/subscribe/'.$newsletter->slug) !!}" class="btn btn-success subnews" role="button">Subscribe</a>
                                                                <a style="display: none" id='unsub_{{$newsletter->slug}}' href="{!! url('members/newsletter/unsubscribe/'.$newsletter->slug) !!}" class="btn btn-warning unsubnews" role="button">Unsubscribe</a>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-3">

            <div class="row">
                <div class="col-md-12">

                    <!-- User thumbnail -->
                    <div class="thumbnail">
                        <div class="thumb thumb-rounded thumb-slide">
                            {!! show_profile_pic($user, 200) !!}
                        </div>
                        @if($user->profile->picture)
                            <div class="text-center">
                                {!! Form::model($user,array('method' => 'POST', 'url' => '/admin/user/removeProfilePicture', 'id' => 'pictureDelete')) !!}
                                <button type="submit" class="btn btn-warning">Remove Profile Picture <i class="far fa-times position-right"></i></button>
                                {!! Form::close() !!}
                            </div>
                        @endif

                        <div class="caption text-center">
                            <h6 class="text-semibold no-margin">{!! $user->profile->first_name !!} {!! $user->profile->last_name !!}<small class="display-block"></small></h6>

                            <div class="text-center">
                                {!! Form::model($user,array('method' => 'POST', 'url' => '/members/account/delete', 'id' => 'userDelete')) !!}
                                <button type="submit" class="btn btn-warning">Delete Account <i class="far fa-times position-right"></i></button>
                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                    <!-- /user thumbnail -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">Active Memberships</h6>
                            <div class="heading-elements">

                            </div>
                            <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-12">
                                    @forelse($userMembership as $membership)
                                        <div class="panel panel-flat">
                                            <div class="panel-heading">
                                                <h6 class="panel-title">{{$membership->membership->title}}</h6>
                                            </div>

                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @if(!isset($membership->subscription))
                                                            Expires : {{$membership->expires ? 'Yes' : 'No'}}<br>
                                                            @if($membership->expires)
                                                                Expires On: {{$membership->expires_on->toDayDateTimeString()}}<br>
                                                            @endif
                                                        @else
                                                            @if($membership->subscription->status == 0)
                                                                Expires : {{$membership->expires ? 'Yes' : 'No'}}<br>
                                                                @if($membership->expires)
                                                                    Expires On: {{$membership->expires_on->toDayDateTimeString()}}<br>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        Status : {{ucfirst($membership->status)}}<br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No Current Membership Plan</p>
                                    @endforelse

                                    @if($userMembership->count() <= 1)
                                            <div class="widget widget-text text-center" style="margin-bottom: -20px;">
                                                <a class="contact-btn cs-color" href="{!! url('/members/upgrade') !!}">Upgrade Membership</a>
                                            </div>
                                        @endif


                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
    <!-- /user profile -->

@stop


