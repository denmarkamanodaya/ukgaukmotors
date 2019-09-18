<div class="auto-specifications detail-content mt-20">
<ul class="row">
    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="element-title">
            <span>Infomation</span>
        </div>
    </li>
    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="specifications-info">
            <ul>
                <li>
                    <span>Type</span>
		    <strong>
                        @if($vehicle->type != '')
			    {{ ucfirst(strtolower($vehicle->type)) }}
			@else
                        @endif
                    </strong>
                </li>
                <li>
                    <span>Engine Size</span>
		    <strong>
			@if($vehicle->engine_size == "")
				
			@else
                        	@if($vehicle->engineSize)
	                            {{ ucfirst(strtolower($vehicle->engineSize->size)) }}
        	                @elseif($vehicle->engine_size != '')
                	            {{ ucfirst(strtolower($vehicle->engine_size)) }}
                        	@endif
			@endif
                    </strong>
                </li>
                <li>
                    <span>Registration</span>
                    <strong>
                        @if($vehicle->registration != '')
                            {{ $vehicle->registration }}
                        @endif
                    </strong>
                </li>
                <li>
                    <span>MOT</span>
                    <strong>
                        @if($vehicle->mot != '')
                            {{ $vehicle->mot }}
                        @endif
                    </strong>
                </li>
                <li>
                    <span>Service History</span>
                    <strong>
                        @if($vehicle->service_history != '')
                            {{ ucfirst(strtolower($vehicle->service_history)) }}
                        @endif
                    </strong>
                </li>

            </ul>
        </div>
    </li>
    <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="specifications-info">
            <ul>
                <li>
                    <span>Mileage</span>
                    <strong>
                        @if($vehicle->mileage != '')
                            {{ $vehicle->mileage }}
                        @endif
                    </strong>
                </li>
                <li>
                    <span>Colour</span>
                    <strong>
                        @if($vehicle->colour != '')
                            {{ ucfirst(strtolower($vehicle->colour)) }}
                        @endif
                    </strong>
                </li>
                <li>
                    <span>Gearbox</span>
                    <strong>
                        @if($vehicle->gearbox != '')
                            {{ ucfirst(strtolower($vehicle->gearbox)) }}
                        @endif
                    </strong>
                </li>
                <li>
                    <span>Fuel Type</span>
                    <strong>
                        @if($vehicle->fuel != '')
                            {{ ucfirst(strtolower($vehicle->fuel)) }}
                        @endif
                    </strong>
                </li>

            </ul>
        </div>
    </li>
</ul>
</div>
