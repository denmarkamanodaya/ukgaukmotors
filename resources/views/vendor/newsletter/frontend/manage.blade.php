@extends('base::frontend.Template')


@section('page_title')

@stop

@section('page_container_class', 'login-container')


@section('meta')
@stop

@section('page_js')
    @if(Settings::get('recaptcha_guest_newsletter') && Settings::get('recaptcha_site_key') != '')
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
@stop

@section('page_css')
@stop

@section('breadcrumbs')
@stop

@section('page-header')

@stop


@section('content')

    <div class="page-section" style="margin-bottom:30px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="cs-signin">
                        <h4 class="content-group">Login to your newsletter account <small class="display-block">Enter your credentials below</small></h4>
                        <div class="row">
                            <form method="POST" action="/newsletter/manage">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul class="ml-10">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (session()->has('status'))
                                    <div class="alert alert-info">
                                        {!! session('status') !!}
                                    </div>
                                @endif

                                    @if (session()->has('flash_notification.message'))
                                        <div class="alert alert-{{ session('flash_notification.level') }}">
                                            {!! session('flash_notification.message') !!}
                                        </div>
                                    @endif

                                {{ session()->forget('flash_notification') }}


                                <div class="cs-field-holder">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Email Address</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="" required>
                                    </div>
                                </div>

                                    @if(Settings::get('recaptcha_guest_newsletter') && Settings::get('recaptcha_site_key') != '')
                                        <div style="margin-left: 15px;" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
                                    @endif

                                <div class="cs-field-holder">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="cs-btn-submit">
                                            <input type="submit" value="Login">
                                        </div>
                                    </div>
                                </div>
                                {!! csrf_field() !!}


                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

