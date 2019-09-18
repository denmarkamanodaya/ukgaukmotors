@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Auctioneers' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneers</span>
@stop


@section('content')
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        @if(Auth::user()->hasRole(Settings::get('main_content_role')))
            <div class="cs-listing-filters">
                <div class="cs-search">
                    {!! Form::open(array('method' => 'POST', 'url' => '/members/auctioneers', 'class' => 'search-form', 'autocomplete' => 'off')) !!}

                    <div class="loction-search vehicle-search">
                        {!! Form::text('name', '', ['class' => '', 'id' => 'search', 'autocomplete' => 'false', 'placeholder' => 'Name', 'tabindex' => '-1']) !!}
                        {!!inputError($errors, 'name')!!}
                    </div>
                    <div class="select-input select-auctioneer">
                        {!! Form::select('auctioneer', $dealerList, 0, ['class' => 'chosen-select', 'id' => 'auctioneerList', 'autocomplete' => 'false', 'data-placeholder' => 'Select Auctioneer', 'tabindex' => '-1']) !!}
                    </div>
                    <div class="select-input select-location">
                        {!! Form::select('location', $dealerCounties, 0, ['class' => 'chosen-select', 'id' => 'auctioneer', 'autocomplete' => 'false', 'data-placeholder' => 'Select Auctioneer', 'tabindex' => '-1']) !!}
                    </div>
                    <div class="cs-field-holder text-center mt-20">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="cs-btn-submit">
                                <input type="submit" value="Filter Auctioneers">
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        @else
            @include('members.NeedUpgrade.sideBannerUpgrade')
        @endif
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if(Auth::user()->hasRole(Settings::get('main_content_role')))
                <div class="cs-agent-map loader">
                {!! Mapper::render() !!}
                </div>
            @endif
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">

                @foreach($dealers as $dealer)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cs-agent-listing">
                            <div class="cs-media">
                                <figure>
                                    @if($dealer->logo != '')
                                        <a href="{!! url('/members/auctioneer/'.$dealer->slug) !!}"><img alt="{{ $dealer->name }}" src="{!! url('/images/dealers/'.$dealer->id.'/thumb150-'.$dealer->logo) !!}"></a>
                                    @endif
                                </figure>
                            </div>
                            <div class="cs-text">
                                <div class="cs-post-title">
                                    <h6><a href="{!! url('/members/auctioneer/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h6>
                                    <span class="cs-color">({{ $dealer->vehiclesCount->aggregate or 0 }} Vehicles)</span>
                                </div>
                                @if(Auth::user()->hasRole(Settings::get('main_content_role')))<address>{{ $dealer->address }}</address>@else @include('members.NeedUpgrade.textAuctioneerOneLine') @endif
                                <a class="contact-btn" href="{!! url('/members/auctioneer/'.$dealer->slug) !!}"><i class="fas fa-gavel"></i>Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="datatable-footer">
                    <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                        Showing {!! $dealers->count() !!} out of {!! $dealers->total() !!}
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                        <nav>{!! $dealers->render() !!}</nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>



@stop


