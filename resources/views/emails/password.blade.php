@extends('emails.Template')



@section('page_js')
@stop

@section('page_css')
@stop


@section('title')
    Password Reset
@stop


@section('content_html')
    <p>Hello,</p>Click here to reset your password: {{ url('password/reset/'.$token) }}
    <p>Best Wishes<br>{!! Settings::get('site_name') !!}</p>
@stop

@section('content_text')
    Hello
    Click here to reset your password: {{ url('password/reset/'.$token) }}

    Best Wishes
    {!! Settings::get('site_name') !!}
@stop


