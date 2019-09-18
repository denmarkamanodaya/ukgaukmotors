@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{ url('theme/4/assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script>
        $(function() {
            // Initialize lightbox
            $('[data-popup="lightbox"]').fancybox({
                padding: 3
            });

        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicles' => '/admin/vehicles', 'Vehicle' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::model($vehicle, array('method' => 'POST', 'url' => '/admin/vehicle/'.$vehicle->slug.'/classifiedEdit/update', 'files' => true, 'id' => 'ClassifiedForm', 'autocomplete' => 'off')) !!}

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">

                        </div>
                        <div class="col-md-10">
                            <h3>{{$vehicle->name}}</h3>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Auction Date :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->auction_date->toFormattedDateString()}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Price :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->price}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Mileage :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->mileage}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Colour :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->colour}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Gearbox :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->gearbox}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Fuel :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->fuel}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Engine Size :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->engine_size}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Make :
                        </div>
                        <div class="col-md-10">
                            {{ ((isset($vehicle->make->name)) ? $vehicle->make->name : 'N/A') }}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Model :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->model->name}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Type :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->vehicleType->name}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Removal Date :
                        </div>
                        <div class="col-md-10">
                            {{$vehicle->auction_date->toFormattedDateString()}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Url :
                        </div>
                        <div class="col-md-10">
                            <a target="_blank" href="{{$vehicle->url}}">{{$vehicle->url}}</a>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>

        @if($vehicle->dealer)
        <div class="col-md-4">


            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">{{ucfirst($vehicle->dealer->type)}} Info</h6>
                            <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>
                        <div class="panel-body text-center">
                            @if($vehicle->dealer->logo)
                                <div class="row">
                                    <div class="col-md-12 text-center mb-10">
                                        <img src="{{ url('/images/dealers/'.$vehicle->dealer->id.'/thumb150-'.$vehicle->dealer->logo)}}">
                                    </div>
                                </div>
                            @endif

                                <div class="row mb-20">
                                    <div class="col-md-12">
                                        {{$vehicle->dealer->name}}
                                    </div>
                                </div>

                                <div class="row mb-20">
                                    <div class="col-md-6">
                                        <a href="{{ url('admin/auctioneer/'.$vehicle->dealer->slug.'/vehicles')}}" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-car"></i></b> View {{ucfirst($vehicle->dealer->type)}}s Vehicles</a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ url('admin/auctioneer/'.$vehicle->dealer->slug)}}" class="btn bg-primary btn-labeled" type="button"><b><i class="fas fa-gavel"></i></b> View {{ucfirst($vehicle->dealer->type)}}</a>
                                    </div>
                                </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
            @endif

    </div>

    <div class="row">
        <div class="col-md-12">
            @if (isset($vehicle->images) && $vehicle->images !== '')
                @php
                    $images = explode(", ", $vehicle->images);
                @endphp
                @if (count($images))
                    @foreach($images as $image)
                        <div class="col-md-2 col-sm-6">
                            <div class="thumbnail">
                                <div class="thumb">
                                    <img alt="" src="{{ $image }}">
                                    <div class="caption-overflow">
                                            <span>
                                                <a class="btn border-white text-white btn-flat btn-icon btn-rounded" rel="gallery" data-popup="lightbox" href="{{ $image }}"><i class="icon-plus3"></i></a>
                                                <a class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5" href="{{ $image }}" target="_blank"><i class="icon-link2"></i></a>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @elseif (count($vehicle->media) > 0)
                @foreach($vehicle->media as $media)
                    <div class="col-md-2 col-sm-6">
                        <div class="thumbnail">
                            <div class="thumb">
                                <img alt="" src="{{showVehicleImage($media)}}">
                                <div class="caption-overflow">
                                        <span>
                                            <a class="btn border-white text-white btn-flat btn-icon btn-rounded" rel="gallery" data-popup="lightbox" href="{{showVehicleImage($media)}}"><i class="icon-plus3"></i></a>
                                            <a class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5" href="{{showVehicleImage($media)}}" target="_blank"><i class="icon-link2"></i></a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@stop


