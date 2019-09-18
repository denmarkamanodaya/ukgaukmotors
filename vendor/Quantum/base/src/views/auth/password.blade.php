@extends('base::frontend.Template')


@section('page_title')@stop

@section('page_container_class', 'login-container')


@section('meta')
@stop

@section('page_js')
    @if(Settings::get('recaptcha_password') && Settings::get('recaptcha_site_key') != '')
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
    <form method="POST" action="/password/email">
        {!! csrf_field() !!}
        <div class="panel panel-body login-form">
            <div class="text-center">
                <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
                <h5 class="content-group">Password recovery <small class="display-block">We'll send you instructions in email</small></h5>
            </div>

            <div class="form-group has-feedback">
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Your email">
                <div class="form-control-feedback">
                    <i class="icon-mail5 text-muted"></i>
                </div>
            </div>
            @if (count($errors) > 0)
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            @if(Settings::get('recaptcha_password') && Settings::get('recaptcha_site_key') != '')
                <div style="margin-left: -12px;" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
            @endif

            <button type="submit" class="btn bg-blue btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>

@stop

