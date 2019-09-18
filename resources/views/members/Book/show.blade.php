@extends('base::members.Template')


@section('page_title', 'Blog')
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Books' => '/members/books', $book->title => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Books</span>
@stop


@section('content')

    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">
                <div class="row">
                    <div class="col-md-12 text-center mb-20">
                        @if($book->front_cover != '')
                            <img src="{!! url($book->front_cover) !!}" id="" class="" style="max-height:200px;">
                        @endif
                        @if($book->back_cover != '')
                            <img src="{!! url($book->back_cover) !!}" id="" class="ml-10" style="max-height:200px;">
                        @endif
                            <h3 class="panel-title mt-20">{{$book->title}}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h5>Contents</h5>
                        @if($book->chapters)
                            @foreach($book->chapters as $chapter)
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    {{$chapter->title}}
                                    @if($chapter->pages)

                                        <ul class="bookList">
                                        @foreach($chapter->pages as $page)
                                           <li><a href="{!! url('/members/book/'.$book->slug.'/'.$chapter->slug.'/'.$page->slug) !!}">{{$page->title}} {!! hasPreview($page->public_view) !!}</a></li>
                                        @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @endif
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
                                    <a href="{!! postLink($post, 'members') !!}"><img alt="" src="{{ url($post->meta->featured_image) }}"></a>
                                </figure>
                            @endif
                        </div>
                        <div class="cs-text">
                            <a href="{!! postLink($post, 'members') !!}">{{ $post->title }}</a>
                            <span><i class="icon-clock5"></i>{{ $post->publish_on->diffForHumans() }}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <a href="{!! url('/members/posts') !!}" class="cs-view-blog">View all Blogs</a>
            </div>
            <div class="widget widget-tags">
                <h6>Tag Cloud</h6>
                @foreach($tags as $tag)
                    <a href="{!! url('/members/tag/'.$tag->slug.'/posts') !!}">{{ ucwords($tag->name) }}</a>
                @endforeach
            </div>
            <div class="widget widget-categories">
                <h6>Top Categores</h6>
                <ul>
                    @foreach($categories as $category)
                        <li>
                            <a href="{!! url('/members/category/'.$category->slug.'/posts') !!}">{{ ucwords($category->name) }} ( @if(isset($category->postCount->aggregate)){{$category->postCount->aggregate}}@else 0 @endif )</a>
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


