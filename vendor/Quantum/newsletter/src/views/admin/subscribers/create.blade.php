@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', 'Create Subscriber' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Create Subscriber
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Subscriber Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/newsletter/subscriber/create')) !!}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
                                {!! Form::text('first_name', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('first_name'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('first_name') }}</span>
                                @endif

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
                                {!! Form::text('last_name', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('last_name'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('email', 'Email Address', array('class' => 'control-label')) !!}
                                {!! Form::text('email', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h5>Newsletter Subscription</h5>
                        <p>Select which newsletters to subscribe them too.</p>

                        <div class="col-md-6">
                            <div class="form-group">
                                @foreach($newsletters as $newsletter)
                                    {!! Form::checkbox('newsletters[]', $newsletter->slug, is_checked_newsletter($newsletter->slug, $selectedNewsletter), array('class' => 'styled')) !!} {!! $newsletter->title !!}<br>

                                @endforeach
                                @if ($errors->has('newsletters'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('newsletters') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h5>Newsletter Options</h5>

                        <div class="col-md-12">
                            <div class="form-group">
                                    {!! Form::checkbox('send_welcome_email', '1', null, array('class' => 'styled')) !!} Send Welcome Email
                                @if ($errors->has('send_welcome_email'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('send_welcome_email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::checkbox('start_responder', '1', null, array('class' => 'styled')) !!} Start Responder Sequence
                                @if ($errors->has('start_responder'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('start_responder') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Create Subscriber<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}

@stop


