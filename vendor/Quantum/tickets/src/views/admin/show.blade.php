@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    <script>
        $('#ticketDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this ticket?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Tickets' => '/admin/tickets', 'View Ticket' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">View Ticket</span>
@stop


@section('content')

    <div class="row mb-20">
        <div class="col-lg-9">

            <div class="panel panel-flat">

                <div class="panel-heading">
                    <h5 class="panel-title">Support Request</h5>
                    <div class="heading-elements">
                    </div>
                </div>


                <div class="panel-body">
                    <div class="col-lg-12 mb-10">{{$ticket->title}}</div>
                    <div class="col-lg-12 mb-10">{!! nl2br(e($ticket->content)) !!}</div>
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
                                <div class="well well-lg" style="background-color:#b2ddf9" ;>
                                    {!! nl2br(e($reply->content)) !!}
                                </div>
                                <div class="col-lg-6 mb-10">Replied : {{$reply->staff->username}}</div>
                                <div class="col-lg-6 text-right mb-10">Replied On : {{$reply->updated_at->toDayDateTimeString()}}</div>
                            @else
                                <div class="well well-lg">
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

                {!! Form::open(array('method' => 'POST', 'url' => '/admin/tickets/ticket/'.$ticket->id.'/reply')) !!}

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::label('content', 'Reply To Ticket', array('class' => 'control-label')) !!}
                            {!! Form::textarea('content', null, array('class' => 'form-control')) !!}
                            @if ($errors->has('content'))
                                <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::label('status', 'Status', array('class' => 'control-label')) !!}
                            {!! Form::select('status', $statuses, 3, array('class' => 'form-control')) !!}
                            @if ($errors->has('status'))
                                <span class="help-block validation-error-label" for="content">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-left">
                    <button type="submit" class="btn btn-primary">Reply<i class="fa fa-comments position-right"></i></button>
                </div>

                {!! Form::close() !!}

            @endif
        </div>


        <div class="col-lg-3">

            <div class="panel panel-flat">

                <div class="panel-heading">
                    <h5 class="panel-title">Ticket Information</h5>
                    <div class="heading-elements">
                    </div>
                </div>


                <div class="panel-body">
                    @if($ticket->email && !$ticket->user)
                        <div class="col-lg-12 text-center mb-10">{{$ticket->email}}</div>
                    @else
                        <div class="col-lg-12 text-center mb-10">{{$ticket->user->email}}</div>
                    @endif
                    <div class="col-lg-6 text-right">User :</div>
                    <div class="col-lg-6 mb-10">{{$ticket->user->username or 'N/A'}}</div>
                    <div class="col-lg-6 text-right">Status :</div>
                    <div class="col-lg-6 mb-10">{{$ticket->status->name}}</div>
                    <div class="col-lg-6 text-right">Department :</div>
                    <div class="col-lg-6 mb-10">{{$ticket->department->name}}</div>
                    <div class="col-lg-6 text-right">Last Update :</div>
                    <div class="col-lg-6 mb-10">{{$ticket->updated_at->diffForHumans()}}</div>
                </div>
            </div>

            <div class="panel panel-flat">

                <div class="panel-heading">
                    <h5 class="panel-title">Change Status</h5>
                    <div class="heading-elements">
                    </div>
                </div>


                <div class="panel-body">
                    <div class="col-lg-12 mb-10">Use the below form to change the ticket status without triggering an email to the user.</div>
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/tickets/ticket/'.$ticket->id.'/changeStatus')) !!}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('status', 'Status', array('class' => 'control-label')) !!}
                                {!! Form::select('status', $statuses, 2, array('class' => 'form-control')) !!}
                                @if ($errors->has('status'))
                                    <span class="help-block validation-error-label" for="content">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-left">
                        <button type="submit" class="btn btn-info">Change Status<i class="fa fa-retweet position-right"></i></button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

            <div class="panel panel-flat">

                <div class="panel-heading">
                    <h5 class="panel-title">Delete Ticket</h5>
                    <div class="heading-elements">
                    </div>
                </div>


                <div class="panel-body">
                    <div class="col-lg-12 mb-10">Delete the ticket using the below button.</div>
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/tickets/ticket/'.$ticket->id.'/delete', 'id' => 'ticketDelete')) !!}

                    <div class="text-left">
                        <button type="submit" class="btn btn-warning">Delete Ticket<i class="far fa-times position-right"></i></button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>

    </div>



@stop


