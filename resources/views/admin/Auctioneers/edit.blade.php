@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', 'Edit Auctioneer\'s Profile' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Edit Auctioneer's Profile</span>
@stop

@section('content')
    {!! Form::open(array('method' => 'POST', 'url' => 'admin/dealers/auctioneer/' . $auctioneer->slug . '/edit', 'class' => 'form-horizontal')) !!}
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">

                            </div>
                            <div class="col-md-10">
                                <h3>{{$auctioneer->name}}</h3>
                            </div>
                        </div>                    

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Address :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('address', isset($auctioneer->address)? $auctioneer->address : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('address'))
                                    <span class="help-block" for="description">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Town :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('town', isset($auctioneer->town)? $auctioneer->town : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('town'))
                                    <span class="help-block" for="description">{{ $errors->first('town') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Postcode :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('postcode', isset($auctioneer->postcode)? $auctioneer->postcode : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('postcode'))
                                    <span class="help-block" for="description">{{ $errors->first('postcode') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                County :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('county', isset($auctioneer->county)? $auctioneer->county : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('county'))
                                    <span class="help-block" for="description">{{ $errors->first('county') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Longitude :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('longitude', isset($auctioneer->longitude)? $auctioneer->longitude : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('longitude'))
                                    <span class="help-block" for="description">{{ $errors->first('longitude') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Latitude :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('latitude', isset($auctioneer->latitude)? $auctioneer->latitude : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('latitude'))
                                    <span class="help-block" for="description">{{ $errors->first('latitude') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                phone :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('phone', isset($auctioneer->phone)? $auctioneer->phone : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('phone'))
                                    <span class="help-block" for="description">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Email :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('email', isset($auctioneer->email)? $auctioneer->email : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block" for="description">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                website :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('website', isset($auctioneer->website)? $auctioneer->website : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('website'))
                                    <span class="help-block" for="description">{{ $errors->first('website') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Auction Url :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('auction_url', isset($auctioneer->auction_url)? $auctioneer->auction_url : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('auction_url'))
                                    <span class="help-block" for="description">{{ $errors->first('auction_url') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Online Bidding Url :
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('online_bidding_url', isset($auctioneer->online_bidding_url)? $auctioneer->online_bidding_url : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('online_bidding_url'))
                                    <span class="help-block" for="description">{{ $errors->first('online_bidding_url') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Details :
                            </div>
                            <div class="col-md-10">
                                {!! Form::textarea('details', isset($auctioneer->details)? $auctioneer->details : '', array('class' => 'form-control')) !!}
                                @if ($errors->has('details'))
                                    <span class="help-block" for="description">{{ $errors->first('details') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save Changes<i class="far fa-save position-right"></i></button>
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
    {!! Form::close() !!}
@stop


