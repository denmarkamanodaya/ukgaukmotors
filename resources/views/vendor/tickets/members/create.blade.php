@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
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

            <!-- Simple panel -->
    <div class="panel panel-flat">

            <div class="panel-heading">
                <h5 class="panel-title">Ticket Details</h5>
                <div class="heading-elements">
                </div>
            </div>


        <div class="panel-body">
            {!! Form::open(array('method' => 'POST', 'url' => '/members/support/createTicket')) !!}


            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('department', 'Department', array('class' => 'control-label')) !!}
                        {!! Form::select('department', $departments,null, array('class' => 'form-control')) !!}
                        @if ($errors->has('department'))
                            <span class="help-block validation-error-label" for="department">{{ $errors->first('department') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('subject', 'Subject', array('class' => 'control-label')) !!}
                        {!! Form::text('subject', null, array('class' => 'form-control')) !!}
                        @if ($errors->has('subject'))
                            <span class="help-block validation-error-label" for="subject">{{ $errors->first('subject') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('content', 'Message', array('class' => 'control-label')) !!}
                        {!! Form::textarea('content', null, array('class' => 'form-control')) !!}
                        @if ($errors->has('content'))
                            <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="cs-field-holder text-left mt-20">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="cs-btn-submit">
                        <input type="submit" value="Create Support Request">
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <!-- /simple panel -->


@stop


