@extends('base::frontend.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
    <meta name="robots" content="noindex, nofollow">
@stop

@section('page_js')
    @if(Settings::get('recaptcha_guest_ticket') && Settings::get('recaptcha_site_key') != '')
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
@stop

@section('page_css')
@stop

@section('breadcrumbs')
@stop

@section('page-header')
    <span class="text-semibold">View Ticket</span>
@stop


@section('content')

    <div class="row mb-20">
        <div class="col-lg-8">

            <div class="panel panel-flat">

                <div class="panel-heading">
                    <h5 class="panel-title">Support Request</h5>
                    <div class="heading-elements">
                    </div>
                </div>


                <div class="panel-body">
                    <div class="col-lg-12 mb-10">{{$ticket->title}}</div>
                    <div class="col-lg-12 mb-10">{{nl2br($ticket->content)}}</div>
                    <div class="col-lg-12 text-right mb-10">Created : {{$ticket->created_at->toDayDateTimeString()}}</div>
                </div>
            </div>

            @if($ticket->replies && $ticket->replies->count() > 0)

                <div class="panel panel-flat">

                    <div class="panel-heading">
                        <h5 class="panel-title">Replies</h5>
                        <div class="heading-elements">
                        </div>
                    </div>


                    <div class="panel-body">
                        @foreach($ticket->replies as $reply)
                            <div class="col-lg-12 mb-10">
                            @if($reply->staff)
                                <div class="well well-lg" style="background-color:#b2ddf9!important; padding: 5px !important;" >
                                    {!! nl2br(e($reply->content)) !!}
                                </div>
                                <div class="col-lg-6 mb-10">Replied : {{$reply->staff->username}}</div>
                                <div class="col-lg-6 text-right mb-10">Replied On : {{$reply->updated_at->toDayDateTimeString()}}</div>
                            @else
                                <div class="well well-lg" style="padding: 5px !important;" >
                                    {!! nl2br(e($reply->content)) !!}
                                </div>
                                <div class="col-lg-12 text-right mb-10">Replied On : {{$reply->updated_at->toDayDateTimeString()}}</div>
                            @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            @endif

            @if($ticket->status->slug != 'closed')
                {!! Form::open(array('method' => 'POST', 'url' => '/support/ticket/'.$ticket->public_key.'/reply')) !!}

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::label('content', 'Reply To Ticket', array('class' => 'control-label')) !!}
                            {!! Form::textarea('content', null, array('class' => 'form-control')) !!}
                            {!! inputError($errors, 'content') !!}
                        </div>
                    </div>
                </div>

                @if(Settings::get('recaptcha_guest_ticket') && Settings::get('recaptcha_site_key') != '')
                    <div style="" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
                    {!! inputError($errors, 'g-recaptcha-response') !!}
                @endif

                <div class="cs-field-holder text-left mt-20">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cs-btn-submit">
                            <input type="submit" value="Reply">
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            @endif

        </div>


        <div class="col-lg-4">

            <div class="panel panel-flat">

                <div class="panel-heading">
                    <h5 class="panel-title">Ticket Information</h5>
                    <div class="heading-elements">
                    </div>
                </div>


                <div class="panel-body">
                    <div class="col-lg-6 text-right">Status :</div>
                    <div class="col-lg-6 mb-10">{{$ticket->status->name}}</div>
                    <div class="col-lg-6 text-right">Department :</div>
                    <div class="col-lg-6 mb-10">{{$ticket->department->name}}</div>
                    <div class="col-lg-6 text-right">Last Update :</div>
                    <div class="col-lg-6 mb-10">{{$ticket->updated_at->diffForHumans()}}</div>
                </div>
            </div>

        </div>

    </div>



@stop


