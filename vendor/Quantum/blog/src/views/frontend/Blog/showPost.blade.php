@extends('frontend.Template')


@section('page_title', 'Blog')
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => url('/'), 'Blog' => '/posts']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Blog</span>
@stop


@section('content')
    <div class="col-md-12">




        <div class="col-md-9">
            <!-- Simple panel -->
            <div class="panel panel-flat">

                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 post-title"><h4>{{ $post->title }}</h4></div>
                            <div class="col-md-12 post-body">@if($post->meta->featured_image)<div class="post_list_image"><img src="{{ url($post->meta->featured_image) }}"></div> @endif{!! $post->summary !!}</div>
                            <div class="col-md-6 post-footer">Posted On : {!! $post->publish_on !!}</div>
                            <div class="col-md-12 post-footer"><hr></div>
                            @if($post->tags->count() > 0)
                                <div class="col-md-12 post-tags"><i class="far fa-tags"> </i> @foreach($post->tags as $tag)
                                        <a href="{{ url('tag/'.$tag->slug.'/posts') }}">{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <!-- /simple panel -->
        </div>


        <div class="col-md-2">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <div class="row">
                        <div class="widget widget-recent-posts">
                            <h4>Recent Posts</h4>

                            <ul>
                                @foreach($latestPosts as $post)
                                    <li>

                                        <div class="cs-media">
                                            @if($post->meta->featured_image)<figure><a href="{!! postLink($post, '') !!}"><img alt="" src="{{ url($post->meta->featured_image) }}"></a></figure>@endif
                                        </div>
                                        <div class="cs-text">
                                            <a href="{!! postLink($post, '') !!}">{{ $post->title }}</a>
                                            <span><i class="icon-clock5"></i>{{ $post->publish_on->diffForHumans() }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="row">

                        <div class="widget widget-tags">
                            <h4>Tag Cloud</h4>

                            <ul>
                                @foreach($tags as $tag)
                                    <a href="{!! url('/tag/'.$tag->slug.'/posts') !!}">{{ ucwords($tag->name) }}</a>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                    <div class="row">

                        <div class="widget widget-categories">
                            <h4>Top Categories</h4>

                            <ul>
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{!! url('/category/'.$category->slug.'/posts') !!}">{{ ucwords($category->name) }} ( @if(isset($category->postCount->aggregate)){{$category->postCount->aggregate}}@else 0 @endif )</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

@stop


