@extends('members.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Members</span> - Dashboard
@stop


@section('content')
            @include('members.partials.dashboard_news')

@stop


