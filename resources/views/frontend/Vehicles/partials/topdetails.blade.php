<div class="auto-overview detail-content" id="vehicle">
    <ul class="row">

        <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            <div class="cs-media">
                <figure><i class="icon-palette cs-color"></i></figure>
            </div>
            <div class="auto-text">
                <span>Colour</span>
                <strong>
                    @if($vehicle->colour != '')
                        {{ $vehicle->colour }}
                    @else
                        n/a
                    @endif
                </strong>
            </div>
        </li>

        <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            <div class="cs-media">
                <figure><i class="icon-vehicle92 cs-color"></i></figure>
            </div>
            <div class="auto-text">
                <span>Mileage</span>
                <strong>
                    @if($vehicle->mileage != '')
                        {{ $vehicle->mileage }}
                    @else
                        n/a
                    @endif
                </strong>
            </div>
        </li>

        <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            <div class="cs-media">
                <figure><i class="icon-driving2 cs-color"></i></figure>
            </div>
            <div class="auto-text">
                <span>Gearbox</span>
                <strong>
                    @if($vehicle->gearbox != '')
                        {{ $vehicle->gearbox }}
                    @else
                        n/a
                    @endif
                </strong>
            </div>
        </li>

        <li class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            <div class="cs-media">
                <figure><i class="icon-gas20 cs-color"></i></figure>
            </div>
            <div class="auto-text">
                <span>Fuel</span>
                <strong>
                    @if($vehicle->fuel != '')
                        {{ $vehicle->fuel }}
                    @else
                        n/a
                    @endif
                </strong>
            </div>
        </li>

    </ul>

</div>