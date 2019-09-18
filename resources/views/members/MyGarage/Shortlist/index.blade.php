@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        var token = '{{ csrf_token() }}';
    </script>
    <script type='text/javascript' src="{{ url('assets/js/shortlist.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'My Garage' => '/members/mygarage', 'Short Listed Vehicles' => 'is_current']) !!}
@stop

@section('sidebarText')
@stop

@section('sidebarFooter')
@stop

@section('page-header')
    <span class="text-semibold">Short Listed Vehicles</span>
@stop


@section('content')
    <div class="row">

        <div class="section-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">


@if(count($vehicles) > 0)
                @foreach($vehicles as $vehicle)
                    @if($viewType == 'classic')
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="auto-listing">

                                <div class="cs-media">
                                    @if (isset($vehicle->images) && $vehicle->images !== '')
                                        @php
                                            $images = explode(", ", $vehicle->images);
                                        @endphp
                                        @if (count($images)) 
                                            <figure> <img src="{!! $images[0] !!}" alt="{{ $vehicle->name }}"></figure>
                                        @endif
                                    @elseif($vehicle->media->count() > 0)
                                            <figure> <img src="{!! showVehicleImage($vehicle->media->first(), false, true) !!}" alt="{{ $vehicle->name }}"></figure>
                                    @else
                                        <figure> <img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></figure>
                                    @endif
                                </div>

                                <div class="auto-text">
                                    <div class="post-title">
                                        <h4><a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h4>
                                        <div class="auto-price">Auction Date : <span class="cs-color">{{ $vehicle->auction_date->format('D, M jS, Y') }}</span></div>
                                    </div>
                                    <ul class="auto-info-detail">
                                        <li>Mileage<span>{{ $vehicle->mileage }}</span></li>
                                        <li>Colour<span>{{ $vehicle->colour }}</span></li>
                                    </ul>

                                    <p>{!! str_limit($vehicle->description, 100) !!}<a href="{!! url('/members/vehicle/'.$vehicle->slug) !!}" class="read-more cs-color">+ More</a></p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($viewType == 'grid')
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            @include('members.Vehicles.partials.vehicleListing')
                        </div>
                    @endif

                @endforeach
    @else
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nothing-found">
                        <p>To add a vehicle to your personal shortlist click on the 'Heart' within the vehicles listing. </p>
                    </div>
                @endif


            </div>
        </div>
    </div>
@stop


