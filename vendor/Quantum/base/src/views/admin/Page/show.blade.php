@extends('base::admin.Template')


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
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), $page->title => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">{!! $page->title !!}</span>
@stop


@section('content')

            <!-- Simple panel -->
    <div class="panel panel-flat">
        @if($page->subtitle != '')
            <div class="panel-heading">
                <h5 class="panel-title">{!! $page->subtitle !!}</h5>
                <div class="heading-elements">
                </div>
            </div>
        @endif

        <div class="panel-body">
            {!! $page->content !!}
        </div>
    </div>
    <!-- /simple panel -->


@stop


