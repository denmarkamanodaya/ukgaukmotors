@if($feed->page == 1)
    <div class="feedTotal">Total Vehicles : {!! $feed->vehicles->total() !!}</div>
@endif
<?php $vi = 1; ?>
@foreach($feed->vehicles as $vehicle)

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('members.Vehicles.partials.vehicleListing', ['mygarage' => true])
        </div>

@endforeach
@include('members.MyGarage.Feed.partials.paginate', ['paginator' => $feed->vehicles])