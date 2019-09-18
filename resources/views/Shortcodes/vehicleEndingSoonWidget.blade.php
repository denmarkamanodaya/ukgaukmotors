<?php
$pageRoute = Auth::check() ? '/members' : '';
?>
<div class="widget featured-listing">
    <h6>Ending Soon</h6>
    <ul>
        @foreach($vehicles as $vehicle)
            <li>
                @if (isset($vehicle->images) && $vehicle->images !== '')
                    @php
                        $images = explode(", ", $vehicle->images);
                    @endphp
                    @if (count($images)) 
                        <div class="cs-media">
                            <figure><a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}"> <img src="{!! $images[0] !!}" alt=""> </a></figure>
                        </div>
                    @endif
                @elseif($vehicle->media->count() > 0)
                    @if(doesVehicleImageExist($vehicle->media->first()))
                        <div class="cs-media">
                            <figure><a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}"> <img src="{!! showVehicleImage($vehicle->media->first(), false, false, 'thumb100-') !!}" alt=""> </a></figure>
                        </div>
                    @endif
                @else
                    <div class="cs-media">
                        <figure> <img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></figure>
                    </div>
                @endif
                <div class="cs-text">
                    <h6><a href="{!! url($pageRoute.'/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h6>
                    <span class="cs-color"><i class="fas fa-gavel"> </i> {{ $vehicle->auction_date->format('D, M jS, Y') }}</span>
                </div>
            </li>
        @endforeach
    </ul>
</div>
