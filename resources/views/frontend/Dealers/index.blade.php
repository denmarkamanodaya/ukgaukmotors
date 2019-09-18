@extends('base::frontend.Template')


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

            @include('frontend.NeedRegister.sideBannerRegister')
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <div class="row">


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">

                @foreach($dealers as $dealer)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cs-agent-listing">
                            <div class="cs-media">
                                <figure>
                                    @if($dealer->logo != '')
                                        <a href="{!! url('/auctioneer/'.$dealer->slug) !!}"><img alt="{{ $dealer->name }}" src="{!! url('/images/dealers/'.$dealer->id.'/thumb150-'.$dealer->logo) !!}"></a>
                                    @endif
                                </figure>
                            </div>
                            <div class="cs-text">
                                <div class="cs-post-title">
                                    <h6><a href="{!! url('/auctioneer/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h6>
                                    <span class="cs-color">({{ $dealer->vehiclesCount->aggregate or 0 }} Vehicles)</span>
                                </div>
                                @include('frontend.NeedRegister.textAuctioneerOffer')
                                <a class="register-free-btn" href="{!! url('/register') !!}">
                                    Register Free
                                    <span class="fa-stack fa-sm">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-play fa-stack-1x"></i>
                                    </span>
                                </a>
                                <a class="contact-btn" href="{!! url('/auctioneer/'.$dealer->slug) !!}"><i class="fas fa-gavel"></i>Details</a>
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


