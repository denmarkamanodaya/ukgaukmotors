@extends('base::frontend.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    @if(Settings::get('recaptcha_guest_ticket') && Settings::get('recaptcha_site_key') != '')
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Support' => '/members/support', 'Create Ticket' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Support</span>
@stop


@section('content')
        {!! $pageSnippet->content !!}
            <!-- Simple panel -->
    <div class="panel panel-flat">

            <div class="panel-heading">
                <h5 class="panel-title">Ticket Details</h5>
                <div class="heading-elements">
                </div>
            </div>


        <div class="panel-body">
            {!! Form::open(array('method' => 'POST', 'url' => '/support')) !!}


            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('department', 'Department', array('class' => 'control-label')) !!}
                        {!! Form::select('department', $departments,null, array('class' => 'form-control')) !!}
                        {!! inputError($errors, 'department') !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('subject', 'Subject', array('class' => 'control-label')) !!}
                        {!! Form::text('subject', null, array('class' => 'form-control', 'required')) !!}
                        {!! inputError($errors, 'subject') !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('content', 'Message', array('class' => 'control-label')) !!}
                        {!! Form::textarea('content', null, array('class' => 'form-control', 'required')) !!}
                        {!! inputError($errors, 'content') !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('email', 'Email Address', array('class' => 'control-label')) !!}
                        {!! Form::email('email', null, array('class' => 'form-control', 'required')) !!}
                        {!! inputError($errors, 'email') !!}
                    </div>
                </div>
            </div>

            @if(Settings::get('recaptcha_guest_ticket') && Settings::get('recaptcha_site_key') != '')
                <div style="" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
                {!! inputError($errors, 'g-recaptcha-response') !!}
            @endif

            <div class="text-left">
                <button type="submit" class="btn btn-primary">Create Support Request<i class="far fa-life-ring position-right"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <!-- /simple panel -->


@stop


