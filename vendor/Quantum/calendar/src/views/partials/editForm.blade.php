<div class="row">
    <div class="col-md-8 col-md-offset-1">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('title', 'Event Title', array('class' => 'control-label')) !!}
                    {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                    {!! inputError($errors, 'title') !!}
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('description', 'Description', array('class' => 'control-label')) !!}
                    {!! Form::textarea('description', $event->meta->description, array('class' => 'form-control', 'id' => 'description', 'required')) !!}
                    {!! inputError($errors, 'description') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 mt-10">
                <div class="form-group">
                    {!! Form::label('all_day_event', 'All Day Event', array('class' => 'control-label')) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::select('all_day_event', ['yes' => 'Yes', 'no' => 'No'],null, array('class' => 'form-control', 'id' => 'all_day_event', 'required')) !!}
                    {!! inputError($errors, 'all_day_event') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">

                    {!! Form::label('start_date', 'Start Date', array('class' => 'control-label')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                        {!! Form::text('start_date', $event->start_day->format('Y-m-d'), array('class' => 'form-control', 'required', 'id' => 'start_date', 'readonly')) !!}
                    </div>
                    {!! inputError($errors, 'start_date') !!}

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group event_time">

                    {!! Form::label('start_time', 'Start Time', array('class' => 'control-label')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                        {!! Form::text('start_time',null, array('class' => 'form-control', 'required', 'id' => 'start_time', 'readonly')) !!}
                    </div>
                    {!! inputError($errors, 'start_time') !!}

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php $i = 0; ?>
                <div id="additional-start-wrap">

                    <div class="row form-group repeat no-display" id="{!! $i !!}-extra_start_dates">
                        <div class="col-md-6">

                            {!! Form::label('extra_start_dates', 'Start Date', array('class' => 'control-label')) !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                                {!! Form::text('extra_start_dates[]',null, array('class' => 'form-control', 'required', 'id' => $i.'-extra_start_dates', 'readonly')) !!}
                            </div>
                            {!! inputError($errors, 'extra_start_dates') !!}
                        </div>
                        <div class="col-md-6">
                            <span id="{!! $i !!}-repeatableid" data-original-title="Remove" data-placement="top" class="btn btn-warning repeatable-del" style="margin-top: 25px"><i class="far fa-times"></i></span>

                        </div>
                    </div>


                </div>
                <span id="add-stat-date" data-original-title="Add" data-placement="top" class="btn btn-primary repeatable-add mb-10"><i class="far fa-calendar-alt"></i>  Add Another Start Date</span>
                <span class="help-block" style="margin-top: -10px; margin-bottom: 10px;">Adding Extra dates will create new events based on the new date, so if you add two extra dates then 3 event in total will be created.</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">

                    {!! Form::label('end_date', 'End Date', array('class' => 'control-label')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                        {!! Form::text('end_date',$event->end_day->format('Y-m-d'), array('class' => 'form-control', 'required', 'id' => 'end_date', 'readonly')) !!}
                    </div>
                    {!! inputError($errors, 'end_date') !!}

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group event_time">

                    {!! Form::label('end_time', 'End Time', array('class' => 'control-label')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                        {!! Form::text('end_time',null, array('class' => 'form-control', 'required', 'id' => 'end_time', 'readonly')) !!}
                    </div>
                    {!! inputError($errors, 'end_time') !!}

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-2 mt-10">
                <div class="form-group">
                    {!! Form::label('repeat_event', 'Repeat Event', array('class' => 'control-label')) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::select('repeat_event', ['yes' => 'Yes', 'no' => 'No'],null, array('class' => 'form-control', 'id' => 'repeat_event', 'required')) !!}
                    {!! inputError($errors, 'repeat_event') !!}
                </div>
            </div>
        </div>

        <div class="row repeat_event">
            <div class="col-md-2 mt-10">
                <div class="form-group">
                    {!! Form::label('repeat_type', 'Repeat Type', array('class' => 'control-label')) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::select('repeat_type', ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'],null, array('class' => 'form-control', 'required')) !!}
                    {!! inputError($errors, 'repeat_type') !!}
                </div>
            </div>
        </div>

        <div class="repeat_event">

            <div class="row repeat_event_months">
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('repeat_months', 'Repeat Months', array('class' => 'control-label')) !!}
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '01', isCalSelected('01', $event->repeat_month), array('class' => 'styled')) !!}<br>Jan
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '02', isCalSelected('02', $event->repeat_month), array('class' => 'styled')) !!}<br>Feb
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '03', isCalSelected('03', $event->repeat_month), array('class' => 'styled')) !!}<br>Mar
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '04', isCalSelected('04', $event->repeat_month), array('class' => 'styled')) !!}<br>Apr
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '05', isCalSelected('05', $event->repeat_month), array('class' => 'styled')) !!}<br>May
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '06', isCalSelected('06', $event->repeat_month), array('class' => 'styled')) !!}<br>Jun
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '07', isCalSelected('07', $event->repeat_month), array('class' => 'styled')) !!}<br>Jul
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '08', isCalSelected('08', $event->repeat_month), array('class' => 'styled')) !!}<br>Aug
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '09', isCalSelected('09', $event->repeat_month), array('class' => 'styled')) !!}<br>Sep
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '10', isCalSelected('10', $event->repeat_month), array('class' => 'styled')) !!}<br>Oct
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '11', isCalSelected('11', $event->repeat_month), array('class' => 'styled')) !!}<br>Nov
                    </div>
                    <div class="form-group" style="display: inline-block; margin-right: 10px;">
                        {!! Form::checkbox('repeat_months[]', '12', isCalSelected('12', $event->repeat_month), array('class' => 'styled')) !!}<br>Dec
                    </div>
                    <span class="help-block" style="margin-top: -10px; margin-bottom: 10px;">Optional : Select Month/s of the year to repeat. Defaults to every month.</span>

                    {!! inputError($errors, 'repeat_months') !!}
                </div>
            </div>

            <div class="row repeat_event_weeks">
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('repeat_weeks', 'Repeat Weeks', array('class' => 'control-label')) !!}
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_weeks[]', 1, isCalSelected('1', $event->repeat_week), array('class' => 'styled')) !!}<br>1
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_weeks[]', 2, isCalSelected('2', $event->repeat_week), array('class' => 'styled')) !!}<br>2
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_weeks[]', 3, isCalSelected('3', $event->repeat_week), array('class' => 'styled')) !!}<br>3
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_weeks[]', 4, isCalSelected('4', $event->repeat_week), array('class' => 'styled')) !!}<br>4
                    </div>
                    <span class="help-block" style="margin-top: -10px; margin-bottom: 10px;">Optional : Select week/s of the month to repeat. Defaults to every week.</span>

                    {!! inputError($errors, 'repeat_weeks') !!}
                </div>
            </div>

            <div class="row repeat_event_days">
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('repeat_days', 'Repeat Days', array('class' => 'control-label')) !!}
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_days[]', 1, isCalSelected(1, $event->repeat_weekday), array('class' => 'styled')) !!}<br>M
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_days[]', 2, isCalSelected(2, $event->repeat_weekday), array('class' => 'styled')) !!}<br>T
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_days[]', 3, isCalSelected(3, $event->repeat_weekday), array('class' => 'styled')) !!}<br>W
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_days[]', 4, isCalSelected(4, $event->repeat_weekday), array('class' => 'styled')) !!}<br>T
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_days[]', 5, isCalSelected(5, $event->repeat_weekday), array('class' => 'styled')) !!}<br>F
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_days[]', 6, isCalSelected(6, $event->repeat_weekday), array('class' => 'styled')) !!}<br>S
                    </div>
                    <div class="form-group" style="display: inline-block">
                        {!! Form::checkbox('repeat_days[]', 0, isCalSelected(0, $event->repeat_weekday), array('class' => 'styled')) !!}<br>S
                    </div>
                    <span class="help-block" style="margin-top: -10px; margin-bottom: 10px;">Optional : Select day/s of the week to repeat. Defaults to day of event.</span>

                    {!! inputError($errors, 'repeat_type') !!}
                </div>
            </div>

            <div class="row mt-20">
                <div class="col-md-2 mt-10">
                    <div class="form-group">
                        {!! Form::label('repeat_amount', 'Repeat Amount', array('class' => 'control-label')) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::number('repeat_amount', null, array('class' => 'form-control', 'id' => 'repeat_amount', 'min' => '0', 'max' => '999')) !!}
                        {!! inputError($errors, 'repeat_amount') !!}
                    </div>
                    <span class="help-block" style="margin-top: -10px; margin-bottom: 10px;">Optional : Select how many times to repeat, leave blank for continuous.</span>
                </div>
            </div>

        </div>


        <hr>
        <h3>Location</h3>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
                    {!! Form::textarea('address', $event->meta->address, array('class' => 'form-control')) !!}
                    {!! inputError($errors, 'address') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('county', 'County', array('class' => 'control-label')) !!}
                    {!! Form::text('county', $event->meta->county, array('class' => 'form-control')) !!}
                    {!! inputError($errors, 'county') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('country_id', 'Country:', array('class' => 'control-label')) !!}
                    {!! Form::select('country_id', $countries, $event->meta->country_id, array('class' => 'form-control', 'id' => 'country_id')) !!}
                    {!!inputError($errors, 'country_id')!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('postcode', 'Postcode', array('class' => 'control-label')) !!}
                    {!! Form::text('postcode', $event->meta->postcode, array('class' => 'form-control')) !!}
                    {!! inputError($errors, 'postcode') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('latitude', 'Latitude', array('class' => 'control-label')) !!}
                    {!! Form::text('latitude', $event->meta->latitude, array('class' => 'form-control')) !!}
                    {!! inputError($errors, 'latitude') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('longitude', 'Longitude', array('class' => 'control-label')) !!}
                    {!! Form::text('longitude', $event->meta->longitude, array('class' => 'form-control')) !!}
                    {!! inputError($errors, 'longitude') !!}
                </div>
            </div>
        </div>

        <hr>
        <h3>Additional</h3>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('event_image', 'Event Image', array('class' => 'control-label')) !!}
                    <button type="button" class="btn btn-primary mb-5" id="lfm2" data-input="event_image" data-preview="thumbnail_event_image">Choose Image <i class="far fa-image position-right"></i></button>
                    {!! Form::text('event_image', $event->meta->event_image, array('class' => 'form-control')) !!}
                    {!! inputError($errors, 'event_image') !!}


                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="row" id="event_image_preview_wrap">
                        <div class="col-md-6 text-center"><img id="thumbnail_event_image" class="" style="max-height:100px;"></div>
                        <div class="col-md-6 text-center"><button type="button" class="btn btn-warning" id="event_image_remove">Remove Image <i class="far fa-times position-right"></i></button></div>

                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('event_url', 'Event Url', array('class' => 'control-label')) !!}
                    {!! Form::text('event_url', $event->meta->event_url, array('class' => 'form-control')) !!}
                    {!! inputError($errors, 'event_url') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-2">
        <div class="col-md-10 col-md-offset-1">
            <h5>Categories</h5>

            @if($categories && $categories != '')
                @foreach($categories as $category)
                    <hr><h6>{{$category->name}}</h6>
                    @foreach($category->children as $child)
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('category[]', $child->id, isCalCategory($child->id,$event->categories), array('class' => 'styled')) !!} {!! $child->name!!}
                            </label>
                        </div>
                    @endforeach

                @endforeach
                {!! inputError($errors, 'categories') !!}
            @endif
        </div>



    </div>

</div>



<hr>
<div class="row">
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Edit Event<i class="far fa-save position-right"></i></button>
    </div>

</div>