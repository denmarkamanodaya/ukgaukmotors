<div class="cs-listing-filters">
    <div class="cs-search">
        {!! Form::open(array('method' => 'POST', 'url' => '/members/vehicles', 'class' => 'search-form')) !!}

        <div class="select-input select-location">
            {!! Form::select('location', $dealerLocation, 0, ['class' => 'chosen-select', 'id' => 'location', 'autocomplete' => 'false', 'data-placeholder' => 'Select Location', 'tabindex' => '-1']) !!}
        </div>
        <div class="select-input select-auctioneer">
            {!! Form::select('auctioneer', $dealerList, 0, ['class' => 'chosen-select', 'id' => 'auctioneer', 'autocomplete' => 'false', 'data-placeholder' => 'Select Company', 'tabindex' => '-1']) !!}
        </div>
        <div class="select-input select-make">
            {!! Form::select('vehicleMake', $vehicleMakes, 0, ['class' => 'chosen-select', 'id' => 'vehicleMake', 'autocomplete' => 'false', 'data-placeholder' => 'Select Make', 'tabindex' => '-1']) !!}
        </div>
        <div class="select-input select-model">
            {!! Form::select('vehicleModel', $vehicleModels, 0, ['class' => 'chosen-select', 'id' => 'vehicleModel', 'autocomplete' => 'false', 'data-placeholder' => 'Select Model', 'tabindex' => '-1']) !!}
        </div>
        <div class="loction-search vehicle-search">
            {!! Form::text('search', '', ['class' => '', 'id' => 'search', 'autocomplete' => 'false', 'placeholder' => 'Search', 'tabindex' => '-1']) !!}
            {!!inputError($errors, 'search')!!}
        </div>
        <div class="cs-field-holder text-center mt-20">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="cs-btn-submit">
                    <input type="submit" value="Search Vehicles">
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

</div>