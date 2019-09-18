@extends('base::frontend.Template')


@section('page_title', $book->title)
@section('body_class', $book->bodyClass)

@section('meta')
@stop

@section('page_js')
    {!! $book->pageJs !!}
    @if (!$book->showBreadcrumbs)
        <script>hideBreadcrumbs = true;</script>
    @endif
@stop

@section('page_css')
    {!! $book->pageCss !!}
@stop

@section('breadcrumbs')
    @if ($book->showBreadcrumbs)
        {!! breadcrumbs(['Home' => url('/'), 'Books' => '/books', $book->title => '/book/'.$book->slug, 'Details' => 'is_current']) !!}
    @endif
@stop

@section('page-header')
    <span class="text-semibold">Books</span>
@stop

@section('pre-content')
    {!! $book->preContent !!}
@stop

@section('content')

    <div class="row">
        <div class="section-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="row">
                    <div class="col-md-12 mb-20">
                        {!! $book->details !!}
                        <div class="mt-10 addthis_inline_share_toolbox"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@stop


