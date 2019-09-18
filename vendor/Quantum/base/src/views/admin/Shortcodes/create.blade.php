@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/shortcode.js')}}"></script>


@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Shortcodes' => '/admin/shortcodes', 'Create' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Shortcode</span> - Create a custom Shortcode
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Shortcode Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/shortcodes/create')) !!}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Shortcode Title', array('class' => 'control-label')) !!}
                                {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('title'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('name', 'Shortcode Name', array('class' => 'control-label')) !!}
                                {!! Form::text('name', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('name') }}</span>
                                @endif
                                <span class="help-block" for="name">This is the identifier used between the two brackets [ ]. Can only consist of alpha-numeric characters, as well as dashes and underscores</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('replace', 'Replace With', array('class' => 'control-label')) !!}

                                {!! Form::textarea('replace', null, array('class' => 'form-control', 'id' => 'replace', 'required')) !!}
                                @if ($errors->has('replace'))
                                    <span class="help-block validation-error-label" for="replace">{{ $errors->first('replace') }}</span>
                                @endif
                                <span class="help-block" for="shortcode">This is what the shortcode will be replaced with.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('description', 'Description', array('class' => 'control-label')) !!}

                                {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'required')) !!}
                                @if ($errors->has('description'))
                                    <span class="help-block validation-error-label" for="description">{{ $errors->first('description') }}</span>
                                @endif
                                <span class="help-block" for="shortcode">Overview of what the shortcode does.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Save Shortcode<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}

@stop


