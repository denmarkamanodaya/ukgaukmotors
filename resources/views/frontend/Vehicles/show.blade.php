@extends('base::frontend.Template')

@section('body-class', 'single-page vehicle-page')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/fotorama.js')}}" type="text/javascript"></script>
@stop

@section('page_css')
    <link href="{{ url('assets/css/fotorama.css')}}" rel="stylesheet" type="text/css">
    <link href="{{Theme::asset('css/print.css', 'members')}}" rel="stylesheet" type="text/css">
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => url('/'), 'Vehicles' => '/vehicles', 'Details' => 'is_current']) !!}
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
                                <figure><a href="{!! url('/vehicle/'.$vehicleImg->slug) !!}"><img data-echo="{!! $images[0] !!}"  src="{!! url('images/blank.gif') !!}" alt=""/></a></figure>
                            </div>
                        </li>
                    @endif
                @elseif ($vehicleImg->media->count() > 0)
                    @if(doesVehicleImageExist($vehicleImg->media->first()))
                        <li>
                            <div class="cs-media">
                                <figure><a href="{!! url('/vehicle/'.$vehicleImg->slug) !!}"><img data-echo="{!! showVehicleImage($vehicleImg->media->first()) !!}"  src="{!! url('images/blank.gif') !!}" alt=""/></a></figure>
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
        <div class="custom-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="page-section">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="car-detail-heading">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="auto-text">
                                        <h2>{{ $vehicle->name }}</h2>
                                        @include('frontend.NeedRegister.buttonNeedRegister')
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
                            @include('frontend.Vehicles.partials.topdetails')

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="auto-specifications images" id="vehicle-images">

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    @if (isset($vehicle->images) && $vehicle->images !== '')
                                                        @php
                                                            $images = explode(", ", $vehicle->images);
                                                        @endphp
                                                        @if (count($images))
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="fotorama" data-width="100%" data-ratio="800/600" data-max-width="100%" data-nav="thumbs" data-arrows="always" data-autoplay="true">
                                                                    @foreach($images as $image)
                                                                        <img src="{{ $image }}">
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @elseif ($vehicle->media->count() > 0)
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="fotorama" data-width="100%" data-ratio="800/600" data-max-width="100%" data-nav="thumbs" data-arrows="always" data-autoplay="true">
                                                                @foreach($vehicle->media as $media)
                                                                    <img src="{{showVehicleImage($media)}}">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                        <div class="addthis_inline_share_toolbox"></div>
                                    </div>

                                </div>
                            </div>


                            <div class="auto-specifications dealer" id="vehicle-dealer">
                                <div class="section-title" style="text-align:left;">
                                    <h4>{{ ucfirst($dealer->type) }} Information</h4>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="cs-admin-info">
                                        <div class="cs-media">
                                            <figure>

                                            </figure>
                                        </div>
                                        <div class="cs-text">
                                            
                                            @include('frontend.NeedRegister.buttonNeedRegister')
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
                                        @include('frontend.Vehicles.partials.vehicleListing', ['vehicle' => $Lvehicle])
                                    </div>
                                @endforeach
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="float-right widget widget-text">
                                        <a class="contact-btn cs-color" href="{!! url('/vehicles?auctioneer='.$dealer->slug) !!}">View All Vehicles</a>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="page-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">

            @include('frontend.NeedRegister.sideBannerRegister')

            @include('frontend.Vehicles.partials.bigDataWidget')


        </aside>
    </div>
@stop


