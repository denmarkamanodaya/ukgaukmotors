@extends('base::frontend.Template')


@section('page_title', 'Blog')
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
        {!! breadcrumbs(['Home' => url('/'), 'Books' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Books</span>
@stop


@section('content')

    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">
                <div class="row">
                    @foreach($books as $book)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-20">
                        <div class="blog-listing medium-view">
                            @if($book->front_cover)
                            <div class="cs-media">
                                <figure>
                                    <a href="{!! url('/book/'.$book->slug) !!}"><img src="{{ url($book->front_cover) }}" alt="" /></a>
                                </figure>
                            </div>
                            @endif
                            <div class="cs-text">
                                <div class="post-title">
                                    <h4><a href="{!! url('/book/'.$book->slug) !!}">{{ $book->title }}</a></h4>
                                </div>
                                <p>{!! $book->content !!}</p>
                                <div class="widget-text col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><a class="contact-btn cs-color" href="{!! url('/book/'.$book->slug.'/details') !!}">View Details</a></div>
                                        <div class="col-md-6 text-right"><a class="contact-btn cs-color" href="{!! url('/book/'.$book->slug) !!}">Read Book</a></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach

                        <div class="datatable-footer">
                            <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                                Showing {!! $books->count() !!} out of {!! $books->total() !!}
                            </div>
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                                <nav>{!! $books->render() !!}</nav>
                            </div>
                        </div>

                </div>
            </div>
        </div>
        <aside class="section-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">

            <div class="widget widget-recent-posts">
                <h6>Recent Posts</h6>
                <ul>
                    @foreach($latestPosts as $post)
                    <li>

                        <div class="cs-media">
                            @if($post->meta->featured_image)
                                <figure>
                                    <a href="{!! postLink($post) !!}"><img alt="" src="{{ url($post->meta->featured_image) }}"></a>
                                </figure>
                            @endif
                        </div>
                        <div class="cs-text">
                            <a href="{!! postLink($post) !!}">{{ $post->title }}</a>
                            <span><i class="icon-clock5"></i>{{ $post->publish_on->diffForHumans() }}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <a href="{!! url('/posts') !!}" class="cs-view-blog">View all Blogs</a>
            </div>
            <div class="widget widget-tags">
                <h6>Tag Cloud</h6>
                @foreach($tags as $tag)
                    <a href="{!! url('/tag/'.$tag->slug.'/posts') !!}">{{ ucwords($tag->name) }}</a>
                @endforeach
            </div>
            <div class="widget widget-categories">
                <h6>Top Categores</h6>
                <ul>
                    @foreach($categories as $category)
                        <li>
                            <a href="{!! url('/category/'.$category->slug.'/posts') !!}">{{ ucwords($category->name) }} ( @if(isset($category->postCount->aggregate)){{$category->postCount->aggregate}}@else 0 @endif )</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            {!! Shortcode::parse('[vehicleLatestWidget amount="4"]') !!}
            {!! Shortcode::parse('[vehicleLatestWidget amount="4" type="classified"]') !!}
            {!! Shortcode::parse('[vehicleEndingSoonWidget amount="4"]') !!}
        </aside>
    </div>


@stop


