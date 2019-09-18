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
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Blog' => 'members/posts', 'Tags' => 'members/posts/tags']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Blog</span>
@stop


@section('content')
    <div class="col-lg-8 col-md-10 col-sm-12">
        <!-- Simple panel -->
        <div class="panel panel-flat">

            <div class="panel-body">
                <div class="col-md-12 post-title"><h4>All Blog Tags</h4></div>
                <div class="col-md-12">
                        <div class="row">
                    @foreach($tags as $tag)
                            <div class="col-md-1 widget-tags">
                                <a href="{!! url('/members/tag/'.$tag->slug.'/posts') !!}">{{ ucwords($tag->name) }}</a>
                            </div>
                            @endforeach

                        </div>
                </div>
            </div>
            <!-- /simple panel -->
        </div>
</div>
        <div class="col-lg-3 col-md-2 col-sm-12">
            <div class="panel panel-flat">

                <div class="panel-body">
                    <div class="col-md-12">

                        <div class="widget widget-tags">
                            <h4>Search</h4>

                            {!! Form::open(array('method' => 'POST', 'url' => '/members/posts/search', 'autocomplete' => 'off', 'id' => 'PostSearch')) !!}
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {!! Form::text('search', null, array('class' => 'form-control', 'required')) !!}
                                        {!!inputError($errors, 'title')!!}

                                    </div>
                                </div>

                                <div class="col-md-3 text-right">
                                    <button type="submit" class="btn btn-primary">Search<i class="far fa-search position-right"></i></button>
                                </div>
                            </div>
                            {!! Form::close() !!}

                        </div>

                        <div class="row">
                            <div class="widget widget-recent-posts">
                                <h4>Recent Posts</h4>

                                <ul>
                                    @foreach($latestPosts as $post)
                                        <li>

                                            <div class="cs-media">
                                                @if($post->meta->featured_image)<figure><a href="{!! postLink($post, 'members') !!}"><img alt="" src="{{ url($post->meta->featured_image) }}"></a></figure>@endif
                                            </div>
                                            <div class="cs-text">
                                                <a href="{!! postLink($post, 'members') !!}">{{ $post->title }}</a>
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
                                        <a href="{!! url('/members/tag/'.$tag->slug.'/posts') !!}">{{ ucwords($tag->name) }}</a>
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
                                            <a href="{!! url('/members/category/'.$category->slug.'/posts') !!}">{{ ucwords($category->name) }} ( @if(isset($category->postCount->aggregate)){{$category->postCount->aggregate}}@else 0 @endif )</a>
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


