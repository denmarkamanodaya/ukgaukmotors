<?php
$isMember = Auth::check();
?>
@foreach($vehicles as $vehicle)
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        @if($isMember)
            @include('members.Vehicles.partials.vehicleListing')
        @else
            @include('frontend.Vehicles.partials.vehicleListing')
        @endif

    </div>
@endforeach
