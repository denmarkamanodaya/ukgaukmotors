@if(count($relatedVehicles) > 0)
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="section-title" style="text-align:left;">
            <h4>You may also like</h4>
        </div>
    </div>
    @foreach($relatedVehicles as $Rvehicle)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="auto-listing auto-grid widget">

                <div class="cs-media">
                    @if (isset($Rvehicle->images) && $Rvehicle->images !== '')
                        @php
                            $images = explode(", ", $Rvehicle->images);
                        @endphp
                        @if (count($images)) 
                            <figure> <a href="{!! url('/members/vehicle/'.$Rvehicle->slug) !!}"><img src="{!! $images[0] !!}" alt="{{ $Rvehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></a></figure>
                        @endif
                    @elseif($Rvehicle->media->count() > 0)
                        @if(doesVehicleImageExist($Rvehicle->media->first()))
                            <figure> <a href="{!! url('/members/vehicle/'.$Rvehicle->slug) !!}"><img src="{!! showVehicleImage($Rvehicle->media->first()) !!}" alt="{{ $Rvehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></a></figure>
                        @endif
                    @endif
                </div>
                <div class="auto-text">
                    <span class="cs-categories"><a href="{!! url('/members/vehicle/'.$Rvehicle->slug) !!}">{{ $Rvehicle->name }}</a></span>
                    <div class="post-title">
                        <div class="auto-price">
                            @if($Rvehicle->vehicle_listing_type == '1')
                                Auction Date : <br><span class="cs-color auction_date">{{ $Rvehicle->auction_date->format('D, M jS, Y') }}</span>
                            @endif
                            @if($Rvehicle->vehicle_listing_type == '2')
                                Classified Vehicle <br><span class="cs-color auction_date">{{ priceAdjust($Rvehicle->price) }}</span>
                            @endif
                        </div>
                    </div>
                    <ul class="auto-info-detail">
                        <li>Mileage<span>{{ $Rvehicle->mileage }}</span></li>
                        <li>Colour<span>{{ $Rvehicle->colour }}</span></li>
                    </ul>

                    <p>{!! str_limit($Rvehicle->description, 100) !!}<a href="{!! url('/members/vehicle/'.$Rvehicle->slug) !!}" class="read-more cs-color">+ More</a></p>
                    <a class="View-btn" href="{!! url('/members/vehicle/'.$Rvehicle->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>
                </div>
            </div>
        </div>
    @endforeach
@endif