@extends('base::frontend.Template')

@section('body-class', 'cs-agent-detail  single-page')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Auctioneers' => '/members/auctioneers', $dealer->name => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneer - {{ $dealer->name }}</span>
@stop


@section('pre-content')
    <!-- Single - Page Slider Start -->
    <div class="cs-banner loader">
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
    <div style="background-color:#fafafa; padding:40px 0;" class="page-section mb-10">
        <div class="container">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="cs-admin-info">
                        <div class="cs-media">
                            <figure>
                                @if($dealer->logo != '')
                                    <a href="{!! url('/auctioneers/'.$dealer->slug) !!}"><img alt="Car auctions {{ $dealer->name }}" src="{!! url('/images/dealers/'.$dealer->id.'/thumb300-'.$dealer->logo) !!}"></a>
                                @endif
                            </figure>
                        </div>
                        <div class="cs-text">
                            <div class="cs-title">
                                <h3>{{ $dealer->name }}</h3>
                            </div>
                            @include('frontend.NeedRegister.textAuctioneerOneLine')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('content')
    <div class="page-section">
        <div class="container">
            <div class="row">
                <div class="section-fullwidth col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

			    <div class="rich_editor_text">
                                <h1>Search all car auctions at {{ $dealer->name }} {{ $dealer->town }} Currently {{ $dealer->vehiclesCount->aggregate or 0 }} cars for sale</h1>
                            {!! nl2br($dealer->details) !!}
                            </div>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="cs-tabs-holder">


                                <div class="widget widget-text">
                                    <h6>Current Vehicles</h6>
                                    <p>We have {{ $dealer->vehiclesCount->aggregate or 0 }} vehicles listed for {{ $dealer->name }}.</p>
                                    <a class="contact-btn cs-color" href="{!! url('/vehicles?auctioneer='.$dealer->slug) !!}">View All Vehicles</a>
                                </div>

                                @if($auctionDays->count() > 0)
                                    <div class="widget widget-text upcomingWidget">
                                        <h6>Upcoming Auctions</h6>
                                        <ul>
                                            @foreach($auctionDays as $auctionDay => $auctionCount)
                                                <li><a href="{!! url('/vehicles?auctioneer='.$dealer->slug.'&auctionDay='.$auctionDay) !!}">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $auctionDay)->format('D, M jS, Y') }} <span class="aCount">({{$auctionCount}} Vehicles)</span></a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


