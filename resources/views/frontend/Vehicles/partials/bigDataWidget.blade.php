<div class="widget myGarageVehicleWidget cs-category-link-icon widgetAdjust mt-20">
    <img class="img-responsive" src="{!! url('/images/big_data_hero.png') !!}">
    <ul>

        @if($vehicle->make)
            @if ($vehicle->make->slug != 'unlisted')
                <li><a target="_blank" href="{!! url('/motorpedia/car-make/'.$vehicle->make->slug) !!}"><i class="fas fa-car cs-color"></i> View {{$vehicle->make->name}} data</a></li>
                @if($vehicle->model)
                    @if ($vehicle->model->slug != 'unlisted')
                        <li><a target="_blank" href="{!! url('/motorpedia/car-make/'.$vehicle->make->slug.'/'.$vehicle->model->slug) !!}"><i class="fas fa-filter cs-color"></i> View {{$vehicle->model->name}} specific data</a></li>
                    @endif

                        @if($vehicle->variant)
                            @if ($vehicle->variant->id != '1')
                                <li><a href="{!! url('/register') !!}"><i class="fas fa-cog cs-color"></i> View Variant Info</a></li>
                            @endif
                        @endif

                @endif

            @else
                <li>No Data Found</li>
            @endif
        @else
            <li>No Data Found</li>
        @endif

    </ul>
</div>