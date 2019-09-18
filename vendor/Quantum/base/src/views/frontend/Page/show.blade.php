@extends('base::frontend.Template')

@section('page_title')
    $page->title
@stop
@section('body_class')
    $page->bodyClass
@stop

@section('meta')
@stop

@section('page_js')
    {!! $page->pageJs !!}
    @if (!$page->showBreadcrumbs)
        <script>var hideBreadcrumbs = true;</script>
    @endif
@stop

@section('page_css')
    {!! $page->pageCss !!}
@stop

@section('breadcrumbs')
@stop

@section('page-header')
    <span class="text-semibold">Pages</span> - Manage site pages
@stop


@section('content')
        <!-- Simple panel -->
    <div class="panel panel-flat">
        <div class="panel-body">
            {!! $page->content !!}
        </div>
    </div>
    <!-- /simple panel -->

@stop


