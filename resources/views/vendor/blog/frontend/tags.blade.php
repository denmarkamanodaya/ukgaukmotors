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
    {!! breadcrumbs(['Home' => url('/'), 'Blog' => '/posts', 'Tags' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Blog</span>
@stop

@section('pre-content')
    <div class="page-section page-intro"><img class="img-responsive=" src="https://gaukmotors.co.uk/media/11-17/photos/Gauk/Motorpedia_About-hero.jpg" /></div>
@stop

@section('content')
    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-20">
                    <h4>All Post Tags</h4>
                        </div>
                    @foreach($tags as $tag)
                        <div class="col-md-3 widget-tags mb-5">
                            <a href="{!! url('/tag/'.$tag->slug.'/posts') !!}">{{ ucwords($tag->name) }}</a>
                        </div>
                    @endforeach

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
                                        <a href="{!! postLink($post, '') !!}"><img alt="" src="{{ url($post->meta->featured_image) }}"></a>
                                    </figure>
                                @endif
                            </div>
                            <div class="cs-text">
                                <a href="{!! postLink($post, '') !!}">{{ $post->title }}</a>
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


