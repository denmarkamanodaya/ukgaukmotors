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
    
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/create', 'files' => true,  'autocomplete' => 'off')) !!}

        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                            	Name:
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name', 'required' => 'required')) !!}
                            </div>
                        </div>  

						<div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                            	Logo:
                            </div>
                            <div class="col-md-10">
                                {!! Form::file('logo') !!}
                            </div>
                        </div>                                            

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Address :
                            </div>
                            <div class="col-md-10">
                                {!! Form::textarea('address', null, array('class' => 'form-control', 'id' => 'address', 'required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Town :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('town', null, array('class' => 'form-control', 'id' => 'town', 'required' => 'required')) !!}
                                
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Postcode :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('postcode', null, array('class' => 'form-control', 'id' => 'postcode', 'required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                County :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('county', null, array('class' => 'form-control', 'id' => 'county', 'required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Longitude :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('longitude', null, array('class' => 'form-control', 'id' => 'longitude')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Latitude :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('latitude', null, array('class' => 'form-control', 'id' => 'latitude')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Phone :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone', 'required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Email :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('email', null, array('class' => 'form-control', 'id' => 'email', 'required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Website :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('website', null, array('class' => 'form-control', 'id' => 'website', 'required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Auction Url :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('auction_url', null, array('class' => 'form-control', 'id' => 'auction_url')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Online Bidding Url :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('online_bidding_url', null, array('class' => 'form-control', 'id' => 'online_bidding_url')) !!}
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-2 text-right text-bold">
                                Details :
                            </div>
                            <div class="col-md-10">
                            	{!! Form::text('details', null, array('class' => 'form-control', 'id' => 'details')) !!}
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
        </div>
    {!! Form::close() !!}
@stop


