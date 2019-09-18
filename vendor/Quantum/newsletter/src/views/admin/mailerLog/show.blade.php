@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => 'admin/newsletter', 'Mail Log' => '/admin/newsletter/maillog', 'Sent Mail' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletters</span> - Mail Log
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Sent Mail Details</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>
                <div class="panel-body">

                    <h3>General</h3>
                    Newsletter : {!! $timedMail->newsletter->title !!}<br>
                    Sent To : {!! $timedMail->sent_count !!}<br>
                    Amount Opened : {!! $timedMail->opened_count !!}<br>
                    <h3> Mail Details</h3>
                    Sent On : {{$timedMail->sent_on->format('d F Y - H:i')}}<br>

                    <h3>Subject</h3>
                    {!! $timedMail->subject !!}

                    <h3>Message</h3>
                    {!! $timedMail->html_message !!}

                </div>
            </div>

        </div>

    </div>

@stop


