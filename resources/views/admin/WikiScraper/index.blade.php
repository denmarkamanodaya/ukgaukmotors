@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Wiki Scraper' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Scrape</span>
@stop


@section('content')
    {!! $page['cleanHtml'] !!}
@stop


