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
        {!! breadcrumbs(['Home' => url('/'), 'Books' => '/books', $book->title => '/book/'.$book->slug, $chapter->title => '/book/'.$book->slug.'/'.$chapter->slug, $page->title => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Books</span>
@stop


@section('content')

    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">


                <div class="row">
                    <div class="col-md-12">
                            @if($page->featured_image != '')
                                <div class="col-md-12 text-center mb-20"><img src="{!! url($page->featured_image) !!}" id="" class="" style="max-height:200px;"></div>
                            @elseif ($chapter && $chapter->featured_imaged != '')
                                <div class="col-md-12 text-center mb-20"><img src="{!! url($chapter->featured_image) !!}" id="" class="" style="max-height:200px;"></div>
                            @endif
                            <div class="text-center mb-20"><h5>{{$page->title}}</h5></div>


                            @if($page->public_view == 1)
                                {!! $page->content !!}
                            @else
                                @include('frontend.NeedRegister.bookButton')
                            @endif



                    </div>
                </div>
                @include('frontend.Book.partials.nav')



            </div>
        </div>
        <aside class="section-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">

            <div class="row">
                    @if($book->front_cover != '')
                        <div class="col-md-12 text-center mb-20"><img src="{!! url($book->front_cover) !!}" id="" class="" style="max-height:200px;"></div>
                    @endif
                    @if($book->back_cover != '')
                            <div class="col-md-12 text-center mb-20"><img src="{!! url($book->back_cover) !!}" id="" class="ml-10" style="max-height:200px;"></div>
                    @endif
            </div>

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


