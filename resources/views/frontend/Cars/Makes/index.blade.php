@extends('base::frontend.Template')


@section('page_title', 'Car Make')
@section('body-class', 'single-post')

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/vehicleSearch.js')}}" type="text/javascript"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => url('/'), 'Motorpedia' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Blog</span>
@stop

@section('pre-content')
    <div class="page-section page-intro page-intro-bc"><img class="img-responsive" src="{!! url('/images/big_data_hero.png') !!}" /></div>
    @include('frontend.Cars.partials.car_data_searchbar')
    <div class="cs-subheader">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-11">
                            <div class="cs-subheader-text make_name_header">
                                <h2>Car Manufacturers</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">
                <div class="row">
                    <div class="alphaListWrap cs-ag-search">
                        <ul class="filter-list">
                            @foreach($carMakesAlpha as $key => $cList)
                                <li><a class="" href="{!! url('/motorpedia/'.$key) !!}">{{$key}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <?php
                    $half = ceil($carMakes->count() / 2);
                    $chunks = $carMakes->chunk(intval($half));
                    ?>

                    @foreach ($chunks as $chunk)
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-5">
                            <div class="row">
                                @foreach ($chunk as $carMake)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-5">

                                        @if($carMake->logo != '')

                                            {!! show_make_logo($carMake, 20) !!}

                                            @else
                                            <span class="mr-20"></span>
                                        @endif
                                        <span class="make_name_list"><a href="{{ url('/motorpedia/car-make/'.$carMake->slug) }}">{{ $carMake->name }}</a></span>
                                    </div>
                                @endforeach
                            </div>
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

            {!! Shortcode::parse('[vehicleLatestWidget amount="4"]') !!}
            {!! Shortcode::parse('[vehicleLatestWidget amount="4" type="classified"]') !!}
            {!! Shortcode::parse('[vehicleEndingSoonWidget amount="4"]') !!}
        </aside>
    </div>


@stop


