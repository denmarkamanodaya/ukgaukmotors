@extends('base::frontend.Template')


@section('page_title', $page->title)
@section('body_class', $page->bodyClass)

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
    <span class="text-semibold">{!! $page->title !!}</span>
@stop

@section('headerContent')
    {!! $page->preContent !!}
@stop

@section('content')
    {!! $page->content !!}
@stop



