@extends('base::members.Template')

@section('body-class', 'single-page vehicle-page')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/fotorama.js')}}" type="text/javascript"></script>
    <script>
        var token = '{{ csrf_token() }}';
    </script>
    <script type='text/javascript' src="{{ url('assets/js/shortlist.js')}}"></script>
    <script type='text/javascript' src="{{ url('assets/js/myGarageWidget.js')}}"></script>

    <script type='text/javascript' src="{{ url('assets/js/js.cookie.min.js') }}"></script>
    <script type='text/javascript' src="{{ url('assets/js/upgrade_slider.js') }}"></script>
@stop

@section('page_css')
    <link href="{{ url('assets/css/fotorama.css')}}" rel="stylesheet" type="text/css">
    <link href="{{Theme::asset('css/print.css', 'members')}}" rel="stylesheet" type="text/css">
@stop

@section('breadcrumbs')
    @include('members.Vehicles.partials.upgradeSlider')
    @if($previous == '/members/mygarage/feed')
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'My Garage' => '/members/mygarage', 'My Feed' => '/members/mygarage/feed', ' Vehicle Details' => 'is_current']) !!}
    @elseif($previous == '/members/mygarage/shortlist')
        {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'My Garage' => '/members/mygarage', 'My Shortlist' => '/members/mygarage/shortlist', ' Vehicle Details' => 'is_current']) !!}
    @else
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Vehicles' => 'go_back', 'Details' => 'is_current']) !!}
    @endif
@stop

@section('page-header')
    <span class="text-semibold">Vehicle - {{ $vehicle->name }}</span>
@stop


@section('pre-content')
    {{--@if($vehicle->media->count() > 0)
        @if(vehicleImageExists($vehicle->media->first()->name, $vehicle->id))
            <div class="vehicleheader cover" style="background: #fff url('{!! url('/images/vehicle/'.$vehicle->id.'/'.$vehicle->media->first()->name) !!}') no-repeat scroll 50% 50%;">
            </div>
        @endif
    @endif--}}
    <!-- Single - Page Slider Start -->
    <div class="cs-banner loader mt-m30">
        <ul class="cs-banner-slider">
            @foreach($dealerVehicleImages as $vehicleImg)
                @if (isset($vehicleImg->images) && $vehicleImg->images !== '')
                    @php
                        $images = explode(", ", $vehicleImg->images);
                    @endphp
                    @if (count($images))
                        <li>
                            <div class="cs-media">
                                    <figure><a href="{!! url('/members/vehicle/'.$vehicleImg->slug) !!}"><img data-echo="{!! $images[0] !!}"  src="{!! url('images/blank.gif') !!}" alt=""/></a></figure>
                            </div>
                        </li>
                    @endif
                @elseif ($vehicleImg->media->count() > 0)
                    @if(doesVehicleImageExist($vehicleImg->media->first()))
                        <li>
                            <div class="cs-media">
                                <figure><a href="{!! url('/members/vehicle/'.$vehicleImg->slug) !!}"><img data-echo="{!! showVehicleImage($vehicleImg->media->first()) !!}"  src="{!! url('images/blank.gif') !!}" alt=""/></a></figure>
                            </div>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div>
    <!-- Single - Page Slider End -->
@stop


@section('content')    
    <div class="row">
        <!-- Main Content -->
        <div class="custom-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="page-section">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="car-detail-heading">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="auto-text">
                                        <h2>{{ $vehicle->name }}</h2>
                                        @if(Auth::user()->hasRole(Settings::get('main_content_role')))<address><i class="icon-location"></i>{{ $dealer->address }}</address>@else @include('members.NeedUpgrade.textAuctioneerOneLine') @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-10">
                                    <div class="dealer-estimate"><span class="cs-color">
                                            @if($vehicle->estimate)
                                                Est: {{ priceAdjust($vehicle->estimate) }}
                                            @endif
                                        </span></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-10">
                                    <div class="auto-price"><span class="cs-color">{{ $vehicle->auction_date->format('D, M jS, Y') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cs-detail-nav">
                            <ul>
                                <li><a href="#vehicle" class="">Vehicle overview</a></li>
                                <li><a href="#vehicle-images" class="">Images</a></li>
                                <li><a href="#vehicle-dealer" class="">{{ ucfirst($dealer->type) }}</a></li>
                            </ul>
                        </div>
                        <div class="on-scroll">
                            @include('members.Vehicles.partials.topdetails')

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="auto-specifications images" id="vehicle-images">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    @if (isset($vehicle->images) && $vehicle->images !== '')
                                                        @php
                                                            $images = explode(", ", $vehicle->images);
                                                        @endphp
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="fotorama" data-width="100%" data-ratio="800/600" data-max-width="100%" data-nav="thumbs" data-arrows="always" data-autoplay="true">
                                                                @foreach($images as $image)
                                                                    <img src="{{ $image }}">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @elseif($vehicle->media->count() > 0)
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="fotorama" data-width="100%" data-ratio="800/600" data-max-width="100%" data-nav="thumbs" data-arrows="always" data-autoplay="true">
                                                                @foreach($vehicle->media as $media)
                                                                    {!! showVehicleImage($media, true) !!}
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 borderb">
                                        @include('members.Vehicles.partials.details')
                                        @if($vehicle->description != '')
                                        <div class="section-title mt-10" style="text-align:left;">
                                            <h6>Description</h6>
                                        </div>
                                        <p class="more-text" style="display: block;">
                                            {!! nl2br($vehicle->description) !!}
                                        </p>
                                        @endif
                                        @if($vehicle->additional_info != '')
                                        <p>{!! nl2br($vehicle->additional_info) !!}</p>
                                        @endif
                                        <div class="addthis_inline_share_toolbox" data-url="{!! url('/vehicle/'.$vehicle->slug) !!}"></div>
                                    </div>

                                </div>
                            </div>



                            <div class="auto-specifications dealer mt-20" id="vehicle-dealer">
                                <div class="section-title" style="text-align:left;">
                                    <h4>{{ ucfirst($dealer->type) }} Information</h4>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="cs-admin-info">
                                        <div class="cs-media">
                                            <figure>
                                                @if($dealer->logo != '')
                                                    <a href="{!! url('/members/auctioneer/'.$dealer->slug) !!}"><img alt="{{ $dealer->name }}" src="{!! url('/images/dealers/'.$dealer->id.'/thumb300-'.$dealer->logo) !!}"></a>
                                                @endif
                                            </figure>
                                        </div>
                                        <div class="cs-text">
                                            <div class="cs-title">
                                                <h3>{{ $dealer->name }}</h3>
                                            </div>
                                            @if(Auth::user()->hasRole(Settings::get('main_content_role')))<address>{{ $dealer->address }}</address>
                                            <ul>
                                                <li>
                                                    <span>Phone number</span>
                                                    {{ $dealer->phone }}

                                                </li>
                                                <li>
                                                    <span>Website</span>
                                                    <a target="_blank" href="{{ $dealer->website }}">{{ $dealer->website }}</a>
                                                </li>
                                            </ul>
                                            @else @include('members.NeedUpgrade.textAuctioneerOneLine') @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-20">
                                    <div class="section-title" style="text-align:left;">
                                        <h4>{{ ucfirst($dealer->type) }}s Latest Vehicles</h4>
                                    </div>
                                </div>
                                @foreach($latestVehicles as $Lvehicle)
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        @include('members.Vehicles.partials.vehicleListing', ['vehicle' => $Lvehicle])
                                    </div>
                                @endforeach

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="float-right widget widget-text">
                                        <a class="contact-btn cs-color" href="{!! url('/members/vehicles?auctioneer='.$dealer->slug) !!}">View All Vehicles</a>
                                    </div>
                                </div>

                                @include('members.Vehicles.relatedModel')

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content End -->

        <!-- Side -->
        <aside class="page-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">
            @if(Auth::user()->hasRole(Settings::get('main_content_role')))

                @if($dealer->longitude != '')
                    <div class="cs-agent-map2 loader">
                        {!! Mapper::render(1) !!}
                    </div>
                @else
                    <div class="">
                        <img src="{!! url('images/Online-side.jpg') !!}" class="img-responsive online_side_img">
                    </div>
                @endif

                <div class="cs-category-link-icon mt-10 mb-10">
                    <ul>
                        @if(in_array($vehicle->id, $shortlist))
                            <li><a class="short-list cs-color" href="{!! url('/members/vehicle/'.$vehicle->slug.'/shortlist') !!}"><i class="icon-heart"></i>ShortList</a></li>
                        @else
                            <li><a class="short-list cs-color" href="{!! url('/members/vehicle/'.$vehicle->slug.'/shortlist') !!}"><i class="icon-heart-o"></i>ShortList</a></li>
                        @endif
                        @if($vehicle->url != '')
                        <li><a target="_blank" href="{!! $vehicle->url !!}"><i class="cs-color fas fa-gavel"></i>View at Auctioneer</a></li>
                        @endif
                        <li><a href="#" onclick="javascript:window.print();"><i class="cs-color icon-print3"></i>Print Details</a></li>
                    </ul>
                </div>
                @include('members.MyGarage.Feed.partials.vehiclePageWidget')
                @include('members.Vehicles.partials.bigDataWidget')

                @else
            @include('members.NeedUpgrade.sideBannerUpgrade')
            @endif
        </aside><!-- Side End -->
    </div><!-- Main Row End -->
    @if(Auth::user()->hasRole(Settings::get('main_content_role')))
    </div></div>
        @if($dealer->longitude != '')
            <div class="cs-agent-map2 loader">
                {!! Mapper::render(0) !!}
            </div>
        @else
            <div class="">
                <img src="{!! url('images/Online-hero.jpg') !!}" class="img-responsive online_hero_img">
            </div>
        @endif
        <div class="page-section" style="padding-top:70px; padding-bottom:0px; margin-bottom: -70px;">
            <div class="container">
    @endif
@stop


