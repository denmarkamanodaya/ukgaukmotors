@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/newsletter.js')}}"></script>


@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletter' => '/admin/newsletter', 'Delete' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Delete Newsletter
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Newsletter Details - {{$curnewsletter->title}}</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/newsletter/'.$curnewsletter->slug.'/delete')) !!}
                    {!! Form::hidden('slug', $curnewsletter->slug) !!}

                    @if($curnewsletter->subscriberCount && $curnewsletter->subscriberCount > 0)
                        <p>Your Newsletter has <b>{{$curnewsletter->subscriberCount}}</b> subscribers, Please choose what to do with them.</p>

                        @if($newsletters)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('newsletterMove', 'Select Newsletter to move subscriber to.', array('class' => 'control-label')) !!}
                                        {!! Form::select("newsletterMove", $newsletters, 0, array('class' => 'form-control mt-15')) !!}
                                        @if ($errors->has('newsletterMove'))
                                            <span class="help-block validation-error-label" for="name">{{ $errors->first('newsletterMove') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">{!! Form::label('start_responder', 'Start the responder sequence. ', array('class' => 'control-label mt-5')) !!}</div>
                                            <div class="col-md-2">{!! Form::select("start_responder", ['yes' => 'Yes', 'no' => 'No'], 'no', array('class' => 'form-control')) !!}
                                        @if ($errors->has('newsletterMove'))
                                            <span class="help-block validation-error-label" for="name">{{ $errors->first('start_responder') }}</span>
                                        @endif
                                            </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                    @else
                        <p>Your Newsletter has no subscribers, Please hit the delete button below.</p>
                    @endif



                    <div class="row mt-20">
                        <div class="text-left">
                            <button type="submit" class="btn btn-danger">Delete Newsletter<i class="far fa-times position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}
    @include('newsletter::admin/email_help')
    {!! Shortcode::showModal() !!}

@stop


