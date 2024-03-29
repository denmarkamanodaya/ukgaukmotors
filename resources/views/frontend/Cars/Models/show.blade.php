@extends('base::frontend.Template')


@section('page_title', 'Car Make')
@section('body-class', 'single-post woocommerce woocommerce-page single single-product')

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/vehicleSearch.js')}}" type="text/javascript"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => url('/'), 'Motorpedia' => '/motorpedia', $carMake->name => '/motorpedia/car-make/'.$carMake->slug.'/#models', $carModel->name => 'is_current']) !!}
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
                        @if($carMake->logo != '')
                        <div class="col-md-1">
                            <div class="make_logo_header">
                                {!! show_make_logo($carMake, 50) !!}
                            </div>
                        </div>
                        @endif
                        <div class="col-md-11">
                            @if($carMake->country)
                                <div class="make_flag_img"><img src="{!! url('/images/flags/'.$carMake->country->flag) !!}"></div>
                            @endif
                            <div class="cs-subheader-text make_name_header">
                                <h2>{{ $carMake->name }} {{ $carModel->name }}</h2>
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
                @if($carModel->description)
                    @if($carModel->description->featured_image)
                        <ul class="blog-detail-slider" style="margin-bottom:30px;">
                            <li>
                                <figure><img src="{{ featured_image($carModel->description->featured_image) }}" alt="" /></figure>
                            </li>
                        </ul>
                    @endif
                @endif

                <div class="woocommerce-tabs wc-tabs-wrapper car-data-tab-adjust">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs wc-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#description" aria-controls="home" role="tab" data-toggle="tab">Description</a>
                        </li>
                        <li role="presentation">
                            <a href="#modelVariants" aria-controls="profile" role="tab" data-toggle="tab">Model Variants</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="description">
                            @if($carModel->description)
                                <div class="cs-blog-detail-text">
                                    @if($carModel->description->content != '')
                                        {!! $carModel->description->content !!}
                                    @else
                                        We are sorry this models description has not yet been completed.<br>Please check back soon as we are constantly updating our data.
                                    @endif
                                </div>
                            @else
                                We are sorry this models description has not yet been completed.<br>Please check back soon as we are constantly updating our data.
                            @endif

                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="modelVariants">
                            @if($carModel->variants->count() > 0)
                                <div class="cs-blog-related-post">
                                    <div class="row">
                                        @foreach($carModel->variants as $variant)
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-5">
                                                <a href="{{ url('motorpedia/car-make/'.$carMake->slug.'/'.$carModel->slug.'/'.$variant->id) }}">{{ $variant->model_desc  }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>





            </div>
        </div>
        <aside class="section-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">
            @include('Shortcodes.relatedMakeWidget')


            {!! Shortcode::parse('[vehicleLatestWidget amount="4"]') !!}
            {!! Shortcode::parse('[vehicleLatestWidget amount="4" type="classified"]') !!}
            {!! Shortcode::parse('[vehicleEndingSoonWidget amount="4"]') !!}

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
        </aside>
    </div>


@stop


