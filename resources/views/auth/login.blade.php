@extends('base::frontend.Template')


@section('page_title')@stop

@section('page_container_class', 'login-container')


@section('meta')
@stop

@section('page_js')
    @if(Settings::get('recaptcha_login') && Settings::get('recaptcha_site_key') != '')
        <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
        <script type="text/javascript" charset="utf-8">
            var onloadCallback = function() {
                grecaptcha.render('recaptcha1', {'sitekey' : '{!! Settings::get('recaptcha_site_key') !!}', 'theme' : 'light' });
                grecaptcha.render('recaptcha2', {'sitekey' : '{!! Settings::get('recaptcha_site_key') !!}', 'theme' : 'light' });
                grecaptcha.render('recaptcha3', {'sitekey' : '{!! Settings::get('recaptcha_site_key') !!}', 'theme' : 'light' });
                grecaptcha.render('recaptcha4', {'sitekey' : '{!! Settings::get('recaptcha_site_key') !!}', 'theme' : 'light' });
            };
        </script>
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
                        <h4 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h4>
                        <div class="row">
                            <form method="POST" action="/auth/login">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
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

                                    @foreach (session('flash_notification', collect())->toArray() as $message)
                                        @if ($message['overlay'])
                                            @include('flash::modal', [
                                                'modalClass' => 'flash-modal',
                                                'title'      => $message['title'],
                                                'body'       => $message['message']
                                            ])
                                        @else
                                            <div class="alert alert-{{ $message['level'] }}">
                                                {!! $message['message'] !!}
                                            </div>
                                        @endif
                                    @endforeach

                                    {{ session()->forget('flash_notification') }}


                                <div class="cs-field-holder">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Email Address</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="" required>
                                    </div>
                                </div>

                                <div class="cs-field-holder">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Password</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <input type="password" name="password" class="form-control" placeholder="" required>
                                    </div>
                                </div>
                                    @if(Settings::get('recaptcha_login') && Settings::get('recaptcha_site_key') != '')

                                    <div class="cs-field-holder">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label></label>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div id="recaptcha1"></div>
                                        </div>
                                    </div>

                                    @endif

                                <div class="cs-field-holder">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="cs-btn-submit">
                                            <input type="submit" value="Login">
                                        </div>
                                        <a data-dismiss="modal" data-target="#user-forgot-pass" data-toggle="modal" class="cs-forgot-password" href="#"><i class="cs-color icon-help-with-circle"></i>Forgot password?</a>
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

