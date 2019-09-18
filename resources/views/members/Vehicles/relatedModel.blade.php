@if(count($relatedVehicles) > 0)
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="section-title" style="text-align:left;">
            <h4>You may also like</h4>
        </div>
    </div>
@foreach($relatedVehicles as $Rvehicle)
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        @include('members.Vehicles.partials.vehicleListing', ['vehicle' => $Rvehicle])
    </div>
@endforeach
    @endif