<div class="auto-listing auto-grid">
    <div class="cs-media">
        @if (isset($vehicle->images) && $vehicle->images !== '')
            @php
                $images = explode(", ", $vehicle->images);
            @endphp
            @if (count($images)) 
                <figure> <a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}"><img src="{!! $images[0] !!}" alt="{{ $vehicle->name }}"></a></figure>
            @endif
        @elseif($vehicle->media->count() > 0)
            <figure> <a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}"><img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="{{ $vehicle->name }}"></a></figure>
        @else
            <figure> <a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}"><img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></a></figure>
        @endif
    </div>
    <div class="auto-text">
        <div class="post-title">
            <h4><a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h4>
            @if($vehicle->vehicle_listing_type == '1')
                <div class="auto-price">Auction Date : <br><span class="cs-color auction_date">{{ $vehicle->auction_date->format('D, M jS, Y') }}</span></div>
            @endif
            @if($vehicle->vehicle_listing_type == '2')
                <div class="auto-price"><b>Classified Listing</b><br><span class="cs-color auction_date">£{{ $vehicle->price }}</span></div>
            @endif
        </div>
        <ul class="auto-info-detail">
            <li>Mileage<span>{{ $vehicle->mileage }}</span></li>
            <li>Colour<span>{{ $vehicle->colour }}</span></li>
        </ul>
        <span class="cs-categories mt-5"><a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></span>

        <p>{!! str_limit($vehicle->description, 100) !!}<a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}" class="read-more cs-color">+ More</a></p>
        @if(Auth::user()->hasRole(Settings::get('main_content_role')))
            @if(in_array($vehicle->id, $shortlist))
                <a class="short-list cs-color" href="{!! url('/members/vehicle/'.$vehicle->slug.'/shortlist') !!}"><i class="icon-heart"></i>ShortList</a>
            @else
                <a class="short-list cs-color" href="{!! url('/members/vehicle/'.$vehicle->slug.'/shortlist') !!}"><i class="icon-heart-o"></i>ShortList</a>
            @endif
        @endif

        <a class="View-btn" href="{!! url('/members/vehicle/'.$vehicle->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>
    </div>
</div>