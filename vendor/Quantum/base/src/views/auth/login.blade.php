@extends('base::frontend.Template')


@section('page_title')@stop

@section('page_container_class', 'login-container')


@section('meta')
@stop

@section('page_js')
    @if(Settings::get('recaptcha_login') && Settings::get('recaptcha_site_key') != '')
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
    <form method="POST" action="/auth/login">
        {!! csrf_field() !!}
        <div class="panel panel-body login-form">
            <div class="text-center">
                @if (session()->has('flash_notification.message'))
                    <div class="alert alert-{{ session('flash_notification.level') }}">
                        {!! session('flash_notification.message') !!}
                    </div>
                @endif
                <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                <h5 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h5>
            </div>

            <div class="form-group has-feedback has-feedback-left">
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
                <div class="form-control-feedback">
                    <i class="icon-mail5 text-muted"></i>
                </div>
            </div>

            <div class="form-group has-feedback has-feedback-left">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
            </div>
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

            @if(Settings::get('recaptcha_login') && Settings::get('recaptcha_site_key') != '')
                <div style="margin-left: -12px;" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
            </div>

            <div class="text-center">
                <a href="{!! url('password/email') !!}">Forgot password?</a>
            </div>
        </div>
    </form>

@stop

