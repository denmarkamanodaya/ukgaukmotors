@extends('base::members.Template')


@section('page_title', 'Blog Search Results')
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    @if(isset($category))
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Blog' => '/members/posts', 'Category' => '/members/posts/categories/', $category->name => 'is_current']) !!}
    @elseif(isset($tag))
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Blog' => '/members/posts', 'Tag' => '/members/posts/tags/', $tag->name => 'is_current']) !!}
    @else
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Blog' => '/members/posts', 'Search Results' => 'is_current']) !!}
    @endif
@stop

@section('page-header')
    <span class="text-semibold">Blog Search Results</span>
@stop

@section('pre-content')
    <div class="page-section page-intro"><img class="img-responsive=" src="https://gaukmotors.co.uk/media/11-17/photos/Gauk/Motorpedia_About-hero.jpg" /></div>
@stop


@section('content')
    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="blog-listing medium-view">
                                @if($post->meta->featured_image)
                                    <div class="cs-media">
                                        <figure>
                                            <a href="{!! postLink($post, 'members') !!}"><img src="{{ url($post->meta->featured_image) }}" alt="" /></a>
                                            @if($post->sticky)
                                                <figcaption>
                                                    <div class="caption-text"><span>STICKY POST</span></div>
                                                </figcaption>
                                            @endif
                                        </figure>
                                    </div>
                                @endif
                                <div class="cs-text">
                                    <div class="post-title">
                                        <h4><a href="{!! postLink($post, 'members') !!}">{{ $post->title }}</a></h4>
                                    </div>
                                    <ul class="cs-auto-categories">
                                        @foreach($post->tags as $tag)
                                            <li><a href="{!! url('/members/tag/'.$tag->slug.'/posts') !!}" class="cs-color">{{ ucwords($tag->name) }}</a></li>
                                        @endforeach
                                    </ul>
                                    <p>{!! $post->summary !!}</p>
                                    <div class="post-detail">
                                        <span class="post-author"><i class="icon-user4"></i> {{ $post->user->username }}</span>
                                        <span class="post-date"> {!! $post->publish_on->format('l jS \\of F Y') !!}</span>
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
                            <nav>{!! $posts->appends(['search' => $search])->render() !!}</nav>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <aside class="section-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">

            <div class="widget widget-tags">
                <h4>Search</h4>
                {!! Form::open(array('method' => 'POST', 'url' => '/members/posts/search', 'autocomplete' => 'off', 'id' => 'PostSearch')) !!}
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            {!! Form::text('search', null, array('class' => 'form-control', 'required')) !!}
                            {!!inputError($errors, 'search')!!}

                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <div class="cs-field-holder">

                            <div class="cs-btn-submit">
                                <input type="submit" value="Search" class="search_button">
                            </div>

                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

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


