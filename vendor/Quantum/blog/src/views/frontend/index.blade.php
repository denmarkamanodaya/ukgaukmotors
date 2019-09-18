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
    @if(isset($category))
        {!! breadcrumbs(['Home' => url('/'), 'Blog' => '/posts', 'Category' => '/posts', $category->name => 'is_current']) !!}
    @elseif(isset($tag))
        {!! breadcrumbs(['Home' => url('/'), 'Blog' => '/posts', 'Tag' => '/posts', $tag->name => 'is_current']) !!}
    @else
        {!! breadcrumbs(['Home' => url('/'), 'Blog' => 'is_current']) !!}
    @endif
@stop

@section('page-header')
    <span class="text-semibold">Blog</span>
@stop


@section('content')
    <div class="col-lg-8 col-md-10 col-sm-12">
        <!-- Simple panel -->
        <div class="panel panel-flat">

            <div class="panel-body">
                <div class="col-md-12">
                    @foreach($posts as $post)
                        <div class="row">
                            <div class="col-md-12 post-list-item">
                                <div class="row">
                                    @if($post->meta->featured_image)
                                        <div class="col-lg-2 col-md-3 col-sm-12 post-body"><div class="post-images"><figure><img src="{{ url($post->meta->featured_image) }}">
                                                    <figcaption>
                                                        <div class="caption-text">
                                                            <span>STICKY POST</span>
                                                        </div>
                                                    </figcaption>
                                                </figure></div> </div>
                                        <div class="col-lg-10 col-md-9 col-sm-12">
                                            @else
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    @endif
                                                    <div class="col-md-12 post-title"><h4><a href="{!! postLink($post, '') !!}">{{ $post->title }}</a></h4></div>
                                                    <div class="col-md-12 post-summary">{!! $post->summary !!}</div>
                                                    <div class="col-md-12"><hr />
                                                        <div class="col-md-6 post-footer"><i class="far fa-user"> </i> {{ $post->user->username }}</div>
                                                        <div class="col-md-6 post-footer"><i class="far fa-calendar"> </i> {!! $post->publish_on->format('l jS \\of F Y') !!}</div>
                                                    </div>
                                                </div>
                                        </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="datatable-footer">
                                <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                                    Showing {!! $posts->count() !!} out of {!! $posts->total() !!}
                                </div>
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                                    {!! $posts->render() !!}
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <!-- /simple panel -->
        </div>

        <div class="col-lg-3 col-md-2 col-sm-12">
            <div class="panel panel-flat">

                <div class="panel-body">
                    <div class="col-md-12">

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


