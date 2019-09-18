@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Emailer' => '/admin/emailer', 'Archive' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Email Archive</span> - Viewing Sent Email
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Archived Details</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>
                <div class="panel-body">

                    @if($email->user)
                    <h3>User</h3>
                    Username : {!! $email->user->username !!}<br>
                    Name : {!! $email->user->profile->first_name !!} {!! $email->user->profile->last_name !!}<br>
                    Email : {!! $email->user->email !!}<br>
                    @endif
                    <h3> Mail Details</h3>
                    Sent On : {{$email->created_at->format('d F Y - H:i')}}<br>

                    <h3>Subject</h3>
                    {!! $email->subject !!}

                    <h3>Message</h3>
                    {!! $email->content_html !!}

                </div>
            </div>

        </div>

    </div>

@stop


