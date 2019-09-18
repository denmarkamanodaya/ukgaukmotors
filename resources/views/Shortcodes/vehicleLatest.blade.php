<?php
$pageRoute = Auth::check() ? '/members' : '';
$padding = 70;
if($type == '2') $padding = 0;
?>
@if($vehicles->count() > 0)
<!--Latest Vehicles Auto Slider Start-->
<div class="page-section" style="background: rgba(237, 240, 245, 1); padding-top:{!! $padding !!}px; padding-bottom:70px;">
    <div class="container">
        <div class="row">
            <div class="section-fullwidtht col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <!--Element Section Start-->
                    <div class="cs-auto-listing cs-auto-box">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="cs-element-title">
                                @if($type == '1')
                                    <h2>Latest Vehicles at Auction</h2>
                                @endif
                                @if($type == '2')
                                    <h2>Latest Classified Vehicles</h2>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="cs-auto-box-slider row">

                                @foreach($vehicles as $vehicle)
                                    @if (isset($vehicle->images) && $vehicle->images !== '')
                                        @php
                                            $images = explode(", ", $vehicle->images);
                                        @endphp
                                        @if (count($images)) 
                                            <li class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="cs-media">
                                                    <figure> <a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}"> <img src="{!! $images[0] !!}" alt="" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"> </a>
                                                        <figcaption> </figcaption>
                                                    </figure>
                                                    <div class="caption-text"> <a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}">
                                                            <h2> {{ $vehicle->name }}</h2>
                                                        </a> </div>
                                                </div>
                                                <div class="auto-text cs-bgcolor">
                                                    @if($vehicle->vehicle_listing_type == '1')
                                                        Auction Date : <span>{{ $vehicle->auction_date->format('D, M jS, Y') }}</span>
                                                    @endif
                                                    @if($vehicle->vehicle_listing_type == '2')
                                                        Price : <span>{{ priceAdjust($vehicle->price) }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                    @elseif($vehicle->media->count() > 0)
                                        @if(doesVehicleImageExist($vehicle->media->first()))
                                            <li class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="cs-media">
                                                    <figure> <a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}"> <img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"> </a>
                                                        <figcaption> </figcaption>
                                                    </figure>
                                                    <div class="caption-text"> <a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}">
                                                            <h2> {{ $vehicle->name }}</h2>
                                                        </a> </div>
                                                </div>
                                                <div class="auto-text cs-bgcolor">
                                                    @if($vehicle->vehicle_listing_type == '1')
                                                        Auction Date : <span>{{ $vehicle->auction_date->format('D, M jS, Y') }}</span>
                                                    @endif
                                                        @if($vehicle->vehicle_listing_type == '2')
                                                            Price : <span>{{ priceAdjust($vehicle->price) }}</span>
                                                        @endif
                                                </div>
                                            </li>
                                        @endif
                                    @else
                                        <li class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="cs-media">
                                                <figure> <a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}"> <img src="{!! url('/images/image-Not-available.jpg') !!}" alt=""> </a>
                                                    <figcaption> </figcaption>
                                                </figure>
                                                <div class="caption-text"> <a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}">
                                                        <h2> {{ $vehicle->name }}</h2>
                                                    </a> </div>
                                            </div>
                                            <div class="auto-text cs-bgcolor">
                                                @if($vehicle->vehicle_listing_type == '1')
                                                    Auction Date : <span>{{ $vehicle->auction_date->format('D, M jS, Y') }}</span>
                                                @endif
                                                @if($vehicle->vehicle_listing_type == '2')
                                                    Price : <span>{{ priceAdjust($vehicle->price) }}</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endif


                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!--Element Section End-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--Latest Model Auto Slider End-->
    @endif