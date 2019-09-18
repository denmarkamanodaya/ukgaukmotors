<?php
$pageRoute = Auth::check() ? '/members' : '';
?>
<div class="catagory-section">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="cs-element-title">
            <h3>Browse cars by make</h3>
            <span class="cs-color">We currently have {{number_format($vehicleCount)}} cars listed</span> </div>
    </div>

    @foreach ($vehicleMakes->chunk($perRow) as $chunk)

        <div class="col-lg-{{$cols}} col-md-{{$cols}} col-sm-{{$cols}} col-xs-12">
            <div class="cs-catagory">
                <ul>
            @foreach ($chunk as $make)
                        <li><a href="{{ url($pageRoute.'/vehicles?vehicleMake='.$make->slug)}}"><span>{{ $make->name }}</span><small>@if($make->vehiclesCount) {{$make->vehiclesCount->aggregate}} @else 0 @endif</small></a></li>
            @endforeach
                </ul>
            </div>
        </div>
    @endforeach

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="button_style cs-button"> <a href="{{ url($pageRoute.'/vehicles')}}">Show all cars</a> </div>
    </div>
</div>




