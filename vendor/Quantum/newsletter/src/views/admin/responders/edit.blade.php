@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/newsletter_email.js')}}"></script>


@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', $newsletter->title => '/admin/newsletter/'.$newsletter->slug.'/responders', 'Create Responder' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Create Newsletter Responder
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Responder Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($responder, array('method' => 'POST', 'url' => '/admin/newsletter/'.$newsletter->slug.'/responder/'.$responder->id.'/edit')) !!}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('subject', 'Newsletter Title', array('class' => 'control-label')) !!}
                                {!! Form::text('subject', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('subject') }}</span>
                                @endif
                                <span class="help-block">The email subject.</span>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('html_message', 'Html Message', array('class' => 'control-label')) !!}
                                {!! Form::textarea('html_message', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('html_message'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('html_message') }}</span>
                                @endif
                                <span class="help-block">The html message.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h5>Message Interval</h5>
                        <p>Select when to send this message after the previous message was sent. If this is the first message in the responder list then it will be sent after the subscriber signs up.<br>
                        Setting Period amount to 0 will instantly send the message.</p>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::number('interval_amount', null, array('class' => 'form-control', 'required', 'min' => 0)) !!}
                                @if ($errors->has('html_message'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('html_message') }}</span>
                                @endif
                                <span class="help-block">Period Amount.</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::select('interval_type', ['Minutes' => 'Minutes', 'Hours' => 'Hours', 'Days' => 'Days', 'Weeks' => 'Weeks', 'Months' => 'Months', 'Years' => 'Years'],null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('html_message'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('html_message') }}</span>
                                @endif
                                <span class="help-block">Period Type.</span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Edit Newsletter Responder<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}
    @include('newsletter::admin/email_help')

@stop


