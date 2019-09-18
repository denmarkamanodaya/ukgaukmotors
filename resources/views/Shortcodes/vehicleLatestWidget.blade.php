<?php
$pageRoute = Auth::check() ? '/members' : '';
?>
@if($vehicles->count() > 0)
<div class="widget featured-listing">

    @if($type == '1')
        <h6>Latest Auction Vehicles</h6>
    @endif
    @if($type == '2')
            <h6>Latest Classified Vehicles</h6>
    @endif
    <ul>
        @foreach($vehicles as $vehicle)
            <li>
                @if (isset($vehicle->images) && $vehicle->images !== '')
                    @php
                        $images = explode(", ", $vehicle->images);
                    @endphp
                    @if (count($images))
                        <div class="cs-media">
                            <figure>
                                <a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}">
                                    <img src="{!! $images[0] !!}" alt="" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';">
                                </a>
                            </figure>
                        </div>
                    @endif
                @elseif($vehicle->media->count() > 0)
                    @if(doesVehicleImageExist($vehicle->media->first()))
                        <div class="cs-media">
                            <figure><a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}">

                                    @if($type == '1')
                                        <img src="{!! showVehicleImage($vehicle->media->first(), false, false, 'thumb100-') !!}" alt="">
                                    @endif
                                    @if($type == '2')
                                            <img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';">
                                    @endif
                                </a></figure>
                        </div>
                    @endif
                @else
                    <div class="cs-media">
                    <figure> <img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></figure>
                    </div>
                @endif
                <div class="cs-text">
                    <h6><a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h6>
                    <span class="cs-color">
                        @if($type == '1')
                            <i class="fas fa-gavel"> </i> {{ $vehicle->auction_date->format('D, M jS, Y') }}
                        @endif
                        @if($type == '2')
                                <i class="far fa-newspaper"> </i> {{ priceAdjust($vehicle->price) }}
                        @endif

                        </span>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endif