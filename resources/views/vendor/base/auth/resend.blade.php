@extends('base::frontend.Template')


@section('page_title')@stop

@section('page_container_class', 'login-container')


@section('meta')
@stop

@section('page_js')

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
                        <h4 class="content-group">Email Validation<small class="display-block">Enter your email address below to resend the validation email</small></h4>
                        <div class="row">
                            <form method="POST" action="/confirm-email/resend">
                                {!! csrf_field() !!}

                                    <div class="text-center">
                                        @if(isset($message))
                                            <p class="bg-info text-center mt-20 mb-20" style="padding: 10px;"><i class="far fa-check"></i> {{$message}}</p>
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
                                    </div>

                                    <div class="form-group has-feedback">
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Your registered email address" required>

                                    </div>
                                    @if (count($errors) > 0)
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                @if(Settings::get('recaptcha_password') && Settings::get('recaptcha_site_key') != '')
                                    <script src='https://www.google.com/recaptcha/api.js'></script>
                                    <div class="cs-field-holder">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div style="" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="cs-field-holder">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="cs-btn-submit">
                                            <input type="submit" value="Resend Verify Email">
                                        </div>
                                    </div>
                                </div>


                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

