@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', 'Auctioneer' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneer</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">

                        </div>
                        <div class="col-md-7">
                            <h3>{{$auctioneer->name}}</h3>

                        </div>

                        <div class="col-md-3 text-right" style="padding-top: 10px;">
                            <a href="{{ url('admin/dealers/auctioneer/'.$auctioneer->slug.'/edit/')}}" class="btn bg-primary btn-labeled" type="button"><b><i class="far fa-edit"></i></b> Edit Profile</a>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Address :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->address}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Town :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->town}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Postcode :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->postcode}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            County :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->county}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Longitude :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->longitude}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Latitude :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->latitude}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            phone :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->phone}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Email :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->email}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            website :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->website}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Auction Url :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->auction_url}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Online Bidding Url :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->online_bidding_url}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Details :
                        </div>
                        <div class="col-md-10">
                            {!! nl2br($auctioneer->details) !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-4">
            @if($auctioneer->logo)
                <div class="row">
                    <div class="col-md-12 text-center mb-10">
                        <img class='img-responsive' src="{{ url('/images/dealers/'.$auctioneer->id.'/'.$auctioneer->logo)}}">
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <a href="{{ url('admin/dealers/auctioneer/'.$auctioneer->slug.'/vehicles')}}" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-car"></i></b> View Vehicles</a>
                    </div>
                </div>

                    </div>

            </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body text-center">
                                <a href="{{ url('admin/dealers/auctioneer/'.$auctioneer->slug.'/vehicles/deleteall')}}" class="btn bg-warning btn-labeled" type="button"><b><i class="far fa-times"></i></b> Delete All Vehicles</a>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body text-center">
                                <a href="{{ url('admin/dealers/auctioneer/'.$auctioneer->slug.'/events/')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-calendar-alt"></i></b> View Dealer Calendar Events</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
			    <div class="panel-body text-center">
				{!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/delete')) !!}
					{!! Form::hidden('slug', $auctioneer->slug, array()) !!}
	                                <button class="btn bg-danger btn-labeled" type="submit"><b><i class="far fa-times"></i></b> Delete Auctioneer</button>
				{!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>

        </div>

    </div>
@stop


