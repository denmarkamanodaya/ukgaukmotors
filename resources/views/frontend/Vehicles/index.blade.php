@extends('base::frontend.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        var token = '{{ csrf_token() }}';
    </script>
    <script type='text/javascript' src="{{ url('assets/js/shortlist.js')}}"></script>
    <script type='text/javascript' src="{{ url('assets/js/vehicleSearch.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => url('/'), 'Vehicles' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicles</span>
@stop


@section('content')
    <div class="row">
        <aside class="section-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="cs-listing-filters">
                <div class="cs-search">
                        {!! Form::open(array('method' => 'POST', 'url' => '/vehicles', 'class' => 'search-form', 'autocomplete' => 'false')) !!}

                    <div class="cs-filter-title"><h6>Search By Keyword</h6></div>
                    <div class="loction-search vehicle-search">
                        {!! Form::text('search', isset($filters->request->search) ? $filters->request->search : '', ['class' => '', 'id' => 'search', 'autocomplete' => 'false', 'placeholder' => 'Search', 'tabindex' => '-1']) !!}
                        {!!inputError($errors, 'search')!!}
                    </div>
                    <hr>

                    <div class="cs-select-model mt-10">
                        <div class="cs-filter-title"><h6>Advanced Search</h6></div>
                        <ul class="cs-checkbox-list" style="height: auto!important;">

                            <li>
                                <div class="checkbox">
                                    <input id="show_auctions" name="listingType[]" type="checkbox" value="auctions" {!! selectedTypeCheckbox('auctions', $filters) !!}>
                                    <label for="show_auctions">Auctions</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <input id="show_classifieds" name="listingType[]" type="checkbox" value="classifieds" {!! selectedTypeCheckbox('classifieds', $filters) !!}>
                                    <label for="show_classifieds">Classifieds</label>
                                </div>
                            </li>

                        </ul>

                    </div>

                    <div class="select-input select-make">
                        {!! Form::select('vehicleMake', $vehicleMakes, searchSelected('vehicleMake', $filters), ['class' => 'chosen-select', 'id' => 'vehicleMake', 'autocomplete' => 'false', 'data-placeholder' => 'Make', 'tabindex' => '-1']) !!}
                    </div>

                    <div class="select-input select-model">
                        {!! Form::select('vehicleModel', $vehicleModels, searchSelected('vehicleModel', $filters), ['class' => 'chosen-select', 'id' => 'vehicleModel', 'autocomplete' => 'false', 'data-placeholder' => 'Model', 'tabindex' => '-1']) !!}
                    </div>

                    <div class="select-input select-location">
                        {!! Form::select('location', $dealerLocation, searchSelected('location', $filters), ['class' => 'chosen-select', 'id' => 'location', 'autocomplete' => 'false', 'data-placeholder' => 'Location', 'tabindex' => '-1']) !!}
                    </div>

                    <div class="select-input select-auctioneer">
                        {!! Form::select('auctioneer', $dealerList, searchSelected('auctioneer', $filters), ['class' => 'chosen-select', 'id' => 'auctioneer', 'autocomplete' => 'false', 'data-placeholder' => 'Company', 'tabindex' => '-1']) !!}
                    </div>

                    <div class="select-input select-day">
                        {!! Form::select('auctionDay', $auctionDays, searchSelected('auctionDay', $filters), ['class' => 'chosen-select', 'id' => 'auctionDay', 'autocomplete' => 'false', 'data-placeholder' => 'Day', 'tabindex' => '-1']) !!}
                    </div>




                    <div class="cs-field-holder text-center mt-20">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="cs-btn-submit">
                                <input type="submit" value="Search">
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            {!! Form::open(array('method' => 'POST', 'url' => '#', 'class' => 'search-form2','id' => 'resetSearch')) !!}
                            <div class="cs-btn-submit">
                                <input type="submit" value="Reset">
                            </div>

                            {!! Form::close() !!}
                        </div>

                    </div>

                </div>

            </div>
        </aside>

        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="auto-sort-filter">
                        <div class="auto-show-resuilt"><span>Showing <em>{!! $vehicles->count() !!}</em> out of {!! $vehicles->total() !!} vehicles</span></div>

                    </div>
                </div>
                @if(count($search['filters']) > 0)
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="auto-your-search">
                        <ul class="filtration-tags">
                            @foreach($search['filters'] as $filter)
                                @if($filter != '')
                                    <li>{{ $filter }} <i class="icon-search"></i></li>
                                @endif
                            @endforeach
                        </ul>
                        <a class="clear-tags cs-color" href="{!! url('/vehicles') !!}">Clear Filters</a>
                    </div>
                </div>
                @endif

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
                                            <figure> <img src="{!! $images[0] !!}" alt="{{ $vehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></figure>
                                        @endif
                                    @elseif ($vehicle->media->count() > 0)
                                        <figure> <img src="{!! showVehicleImage($vehicle->media->first()) !!}" alt="{{ $vehicle->name }}" onerror="this.onerror=null; this.src='{!! url('/images/image-Not-available.jpg') !!}';"></figure>
                                    @else
                                        <figure> <img src="{!! url('/images/image-Not-available.jpg') !!}" alt="{{ $vehicle->name }}"></figure>
                                    @endif
                                </div>

                                <div class="auto-text">
                                    <div class="post-title">
                                        <h4><a href="{!! url('/vehicle/'.$vehicle->slug) !!}">{{ $vehicle->name }}</a></h4>
                                        @if($vehicle->vehicle_listing_type == '1')
                                            <div class="auto-price">Auction Date : <span class="cs-color">{{ $vehicle->auction_date->format('D, M jS, Y') }}</span></div>
                                        @endif
                                    </div>
                                    <ul class="auto-info-detail">
                                        <li>Mileage<span>{{ $vehicle->mileage }}</span></li>
                                        <li>Colour<span>{{ $vehicle->colour }}</span></li>
                                    </ul>

                                    <p>{!! str_limit($vehicle->description, 100) !!}<a href="{!! url('/vehicle/'.$vehicle->slug) !!}" class="read-more cs-color">+ More</a></p>
                                </div>
                            </div>
                        </div>
                    @endif

                        @if($viewType == 'grid')
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                @include('frontend.Vehicles.partials.vehicleListing')
                            </div>
                        @endif

                @endforeach
                <div class="datatable-footer">
                    <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                        Showing {!! $vehicles->count() !!} out of {!! $vehicles->total() !!}
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                        {!! $vehicles->appends($search['pagination'])->render() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop


