
<?php
    $isRecent = isRecentVehicle($vehicle);
    $recentClass = '';
if($isRecent)
{
    $recentClass = 'recentVehicleTag';
    if(isset($vi) && $vi == 1) $recentClass = 'recentFirst '.$recentClass;
}
?>
@if($vehicle->vehicle_listing_type == '1')
    <div class="auto-listing auto-grid">
        <div class="cs-media vehicleListing {{isset($recentClass) ? $recentClass : null}}">
            @if (isset($vehicle->images) && $vehicle->images !== '')
                @php
                    $images = explode(", ", $vehicle->images);
                @endphp
                @if (count($images))
                    <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! $images[0] !!}" alt="{{ $vehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></a></figure>
                @endif
            @elseif ($vehicle->media->count() > 0)
                <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="{{ $vehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></a></figure>
            @else
                <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></a></figure>
            @endif
                <p class="typeIcon"><span class="isRecentTag"></span><img src="/images/auction_icon.png"></p>
                @if(isset($isRecent) && $isRecent)
                    <p class="recent"><span class="isRecentTag">Recent</span></p>
                @endif
        </div>
        <div class="auto-text">
            <div class="post-title">
                <h4><a href="{!! url('/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h4>
                    <div class="auto-price">Auction Date : <br><span class="cs-color auction_date">{{ $vehicle->auction_date->format('D, M jS, Y') }}</span></div>
                @if($vehicle->estimate && $vehicle->estimate != '')
                    <div class="auto-price">Est : <span class="cs-color auction_date">{{ listPrice($vehicle->estimate) }}</span></div>
                @endif
            </div>

            <span class="cs-categories mb-5"><a href="{!! url('/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></span>

            <div class="">
                <a class="View-btn" href="{!! url('/vehicle/'.$vehicle->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>
            </div>
        </div>
    </div>
@endif

@if($vehicle->vehicle_listing_type == '2')
    <div class="auto-listing auto-grid">
        <div class="cs-media vehicleListing {{isset($recentClass) ? $recentClass : null}}">
            @if (isset($vehicle->images) && $vehicle->images !== '')
                @php
                    $images = explode(", ", $vehicle->images);
                @endphp
                @if (count($images))
                    <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! $images[0] !!}" alt="{{ $vehicle->name }}"></a></figure>
                @endif
            @elseif ($vehicle->media->count() > 0)
                <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="{{ $vehicle->name }}"></a></figure>
            @else
                <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></a></figure>
            @endif
                <p class="typeIcon"><span class="isRecentTag"></span><img src="/images/classified_icon.png"></p>
                @if(isset($isRecent) && $isRecent)
                    <p class="recent"><span class="isRecentTag">Recent</span></p>
                @endif
        </div>
        <div class="auto-text classifiedBox">
            <div class="post-title">
                <h4><a href="{!! url('/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h4>
                    <div class="auto-price"><b>Classified Listing</b><br><span class="cs-color auction_date">{{ $vehicle->estimate }}</span></div>
            </div>

            <span class="cs-categories mb-5"><a href="{!! url('/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></span>

        </div>
        <div class="vehicleDetailWrap_{{$vehicle->vehicle_listing_type}}">
            <a class="View-btn" href="{!! url('/vehicle/'.$vehicle->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>
        </div>

    </div>
@endif
