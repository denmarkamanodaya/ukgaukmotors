@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', 'Subscribers' => '/admin/newsletter/subscribers', 'Edit Subscriber' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Edit Subscriber
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
                    {!! Form::model($subscriber, array('method' => 'POST', 'url' => '/admin/newsletter/subscriber/'.$subscriber->id.'/edit')) !!}




                    @if($subscriber->user)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    This subscriber is a registered member. To change their subscription details you can edit their main profile.<br>
                                    <a href="{!! url('admin/user/'.$subscriber->user->username.'/edit') !!}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-user"></i></b> Edit Profile</a></a>
                                </div>
                            </div>
                        </div>
                    @else
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
                                    {!! Form::text('last_name', null, array('class' => 'form-control')) !!}
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
                    @endif

                    <div class="row">
                        <h5>Newsletter Subscription</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::checkbox('removeFromNewsletter', '1', false, array('class' => 'styled')) !!} Remove from this newsletter ?
                                @if ($errors->has('email'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('moveToNewsletter', 'Move To Newsletter', array('class' => 'control-label')) !!}
                                {!! Form::select('moveToNewsletter', $newsletters, null, array('class' => 'form-control')) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>


                    @if($subscriber->otherNewsletter && $subscriber->otherNewsletter->count() > 0)
                    <div class="row">
                        <h5>Other Newsletter Subscriptions</h5>
                        <p>Manage their other newsletter subscriptions, Just untick to remove them from the newsletter</p>

                        <div class="col-md-6">
                            <div class="form-group">
                                @foreach($subscriber->otherNewsletter as $otherNewsletter)
                                    <div class="col-md-12">{!! Form::checkbox('otherNewsletters[]', $otherNewsletter->newsletter->id, true, array('class' => 'styled')) !!} {!! $otherNewsletter->newsletter->title !!}</div>

                                @endforeach
                                @if ($errors->has('newsletters'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('newsletters') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif


                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Edit Subscriber<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}

@stop


