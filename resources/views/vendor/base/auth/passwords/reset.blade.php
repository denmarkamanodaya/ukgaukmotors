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

                        <form method="POST" action="/password/reset" autocomplete="off">
                            {!! csrf_field() !!}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <h4 class="content-group">Password reset <small class="display-block">You can reset your password below.</small></h4>

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
                                    {!! Form::label('password', 'Password', array('class' => 'control-label')) !!}
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    {!! Form::password('password', null, array('class' => 'form-control', 'required')) !!}
                                    {!!inputError($errors, 'password')!!}
                                </div>
                            </div>

                            <div class="cs-field-holder">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    {!! Form::label('password_confirmation', 'Repeat Password', array('class' => 'control-label')) !!}
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    {!! Form::password('password_confirmation', null, array('class' => 'form-control', 'required')) !!}
                                    {!!inputError($errors, 'password_confirmation')!!}
                                </div>
                            </div>

                            @if (count($errors) > 0)
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="cs-field-holder">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="cs-btn-submit">
                                        <input type="submit" value="Reset password">
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

