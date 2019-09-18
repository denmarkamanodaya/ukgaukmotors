@if(count($relatedVehicles) > 0)
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="section-title" style="text-align:left;">
            <h4>You may also like</h4>
        </div>
    </div>
    @foreach($relatedVehicles as $vehicle)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="auto-listing auto-grid widget">

                <div class="cs-media">
                    @if (isset($vehicle->images) && $vehicle->images !== '')
                        @php
                            $images = explode(", ", $vehicle->images);
                        @endphp
                        @if (count($images))
                            <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! $images[0] !!}" alt="{{ $vehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></a></figure>
                        @endif
                    @elseif ($vehicle->media->count() > 0)
                        @if(doesVehicleImageExist($vehicle->media->first()))
                            <figure> <a href="{!! url('/vehicle/'.$vehicle->slug) !!}"><img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="{{ $vehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></a></figure>
                        @endif
                    @endif
                </div>
                <div class="auto-text">
                    <span class="cs-categories"><a href="{!! url('/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></span>
                    <div class="post-title">
                        <div class="auto-price">
                            @if($vehicle->vehicle_listing_type == '1')
                                Auction Date : <br><span class="cs-color auction_date">{{ $vehicle->auction_date->format('D, M jS, Y') }}</span>
                            @endif
                            @if($vehicle->vehicle_listing_type == '2')
                                Classified Vehicle <br><span class="cs-color auction_date">{{ priceAdjust($vehicle->price) }}</span>
                            @endif
                        </div>
                    </div>
                    <ul class="auto-info-detail">
                        <li>Mileage<span>{{ $vehicle->mileage }}</span></li>
                        <li>Colour<span>{{ $vehicle->colour }}</span></li>
                    </ul>

                    <p>{!! str_limit($vehicle->description, 100) !!}<a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}" class="read-more cs-color">+ More</a></p>
                    <a class="View-btn" href="{!! url('/members/vehicle/'.$vehicle->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>
                </div>
            </div>
        </div>
    @endforeach
@endif