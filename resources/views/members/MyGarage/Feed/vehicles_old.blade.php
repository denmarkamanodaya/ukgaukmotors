@if($feed->page == 1)
    <div class="feedTotal">Total Vehicles : {!! $feed->vehicles->total() !!}</div>
@endif
<?php $vi = 1; ?>
@foreach($feed->vehicles as $vehicle)
<?php
$recentClass = '';
$isRecent = isRecentVehicle($vehicle);
if($isRecent)
    {
        $recentClass = 'recentVehicleTag';
        if($vi == 1) $recentClass = 'recentFirst '.$recentClass;
    }
?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="auto-listing auto-grid">
                <div class="cs-media {{$recentClass}}">
                    @if (isset($vehicle->images) && $vehicle->images !== '')
                        @php
                            $images = explode(", ", $vehicle->images);
                        @endphp
                        @if (count($images)) 
                            <figure> <a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}"><img src="{!! $images[0] !!}" alt="{{ $vehicle->name }}"></a></figure>
                        @endif
                    @elseif($vehicle->media->count() > 0)
                        @if(doesVehicleImageExist($vehicle->media->first()))
                            <figure> <a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}"><img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="{{ $vehicle->name }}"></a></figure>
                        @endif
                    @else
                        <figure> <a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}"><img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></a></figure>
                    @endif
                    @if($isRecent)
                            <p><span class="isRecentTag">Recent</span></p>
                        @endif
                </div>
                <div class="auto-text">
                    <span class="cs-categories"><a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></span>
                    <div class="post-title">
                        <h4><a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h4>
                        @if($vehicle->vehicle_listing_type == 1)
                            <div class="auto-price">Auction Date : <br><span class="cs-color auction_date">{{ $vehicle->auction_date->format('D, M jS, Y') }}</span></div>
                            @if($vehicle->estimate && $vehicle->estimate != '')
                                <div class="auto-price">Est : <span class="cs-color auction_date">{{ listPrice($vehicle->estimate) }}</span></div>
                            @endif
                        @elseif($vehicle->vehicle_listing_type == 2)
                            <div class="auto-price"><span class="cs-color auction_date">{{ listPrice($vehicle->price) }}</span></div>
                        @endif
                    </div>
                    <ul class="auto-info-detail">
                        <li>Mileage<span>{{ $vehicle->mileage }}</span></li>
                        <li>Colour<span>{{ $vehicle->colour }}</span></li>
                    </ul>

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
        </div>

@endforeach
@include('members.MyGarage.Feed.partials.paginate', ['paginator' => $feed->vehicles])