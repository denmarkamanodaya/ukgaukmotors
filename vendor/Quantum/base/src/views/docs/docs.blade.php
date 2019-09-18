@extends('base::admin.Template')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Documentation' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Documentation</span> - Developer
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">{!! $index !!}</div>
        <div class="col-md-9">{!! $content !!}</div>
    </div>
@endsection