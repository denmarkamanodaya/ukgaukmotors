@extends('base::members.Template')


@section('page_title', $page->title)
@section('body_class', $page->bodyClass)

@section('meta')
@stop

@section('page_js')
    {!! $page->pageJs !!}
    @if (!$page->showBreadcrumbs)
        <script>hideBreadcrumbs = true;</script>
    @endif
@stop

@section('page_css')
    {!! $page->pageCss !!}
@stop

@section('breadcrumbs')
    @if ($page->showBreadcrumbs)
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), $page->title => 'is_current']) !!}
    @endif
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


