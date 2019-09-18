@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="cs-listing-filters">
    <div class="cs-search">
        {!! Form::open(array('method' => 'POST', 'url' => '/members/mygarage/feed/add', 'class' => 'search-form')) !!}
        <div class="loction-search set-title">
            {!! Form::text('title', '', ['class' => '', 'id' => 'search', 'autocomplete' => 'false', 'placeholder' => 'Feed Title', 'tabindex' => '-1', 'required']) !!}
            {!!inputError($errors, 'title')!!}
        </div>
        <hr>

        <div class="loction-search vehicle-search">
            {!! Form::text('search', '', ['class' => '', 'id' => 'search', 'autocomplete' => 'false', 'placeholder' => 'Search', 'tabindex' => '-1']) !!}
            {!!inputError($errors, 'search')!!}
        </div>

        <div class="select-input select-make">
            {!! Form::select('vehicleMake', $vehicleMakes, 0, ['class' => 'chosen-select', 'id' => 'vehicleMake', 'autocomplete' => 'false', 'data-placeholder' => 'Make', 'tabindex' => '-1']) !!}
        </div>

        <div class="select-input select-model">
            {!! Form::select('vehicleModel', $vehicleModels, 0, ['class' => 'chosen-select', 'id' => 'vehicleModel', 'autocomplete' => 'false', 'data-placeholder' => 'Model', 'tabindex' => '-1']) !!}
        </div>

        <div class="select-input select-location">
            {!! Form::select('location', $dealerLocation, 0, ['class' => 'chosen-select', 'id' => 'location', 'autocomplete' => 'false', 'data-placeholder' => 'Location', 'tabindex' => '-1']) !!}
        </div>

        <div class="select-input select-auctioneer">
            {!! Form::select('auctioneer', $dealerList, 0, ['class' => 'chosen-select', 'id' => 'auctioneer', 'autocomplete' => 'false', 'data-placeholder' => 'Auctioneer', 'tabindex' => '-1']) !!}
        </div>

        <div class="select-input select-day">
            {!! Form::select('auctionDay', $auctionDays, 0, ['class' => 'chosen-select', 'id' => 'auctionDay', 'autocomplete' => 'false', 'data-placeholder' => 'Day', 'tabindex' => '-1']) !!}
        </div>


        <div class="cs-field-holder text-center mt-20">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="cs-btn-submit">
                    <input type="submit" value="Add Feed">
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

</div>