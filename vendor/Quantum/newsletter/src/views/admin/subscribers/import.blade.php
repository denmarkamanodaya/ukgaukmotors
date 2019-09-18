@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', 'Import Subscribers' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Import Subscribers</span> - Import newsletter subscribers
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Import Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/newsletter/import', 'files' => true)) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                The import requires a csv file of subscribers to import. The csv must be in the following format..<br><br>
                                <p>email,first_name,last_name</p>
                                {!! Form::label('importcsv', 'Import CSV File', ['class' => 'control-label']) !!}
                                {!! Form::file('importcsv', array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('importcsv'))
                                    <script>formErrors.push("importcsv");</script>
                                    <span class="help-block validation-error-label" for="importcsv">{!! $errors->first('importcsv') !!}</span>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('newsletter', 'Newsletter', array('class' => 'control-label')) !!}
                                {!! Form::select('newsletter', $newsletters, null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('newsletter'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('newsletter') }}</span>
                                @endif
                                <span class="help-block">Select which newsletter to import them to.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('send_welcome', '1', null, array('class' => 'styled')) !!} Send the Welcome Email
                                    </label>
                                </div>
                                @if ($errors->has('send_welcome'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('send_welcome') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('start_responder', '1', null, array('class' => 'styled')) !!} Start the responder sequence
                                    </label>
                                </div>
                                @if ($errors->has('start_responder'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('start_responder') }}</span>
                                @endif
                                <span class="help-block">Start any responder sequence attached to this newsletter.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Import Subscribers<i class="far fa-share position-right"></i></button>
                        </div>

                    </div>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>

    </div>
@stop


