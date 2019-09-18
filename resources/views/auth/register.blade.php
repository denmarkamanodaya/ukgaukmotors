<div class="register-form cs-register">
    {!! Form::open(array('method' => 'POST', 'url' => 'register', 'autocomplete' => 'off')) !!}

    <div class="cs-field-holder">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::label('username', 'Username', array('class' => 'control-label')) !!}
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::text('username', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'username')!!}
        </div>
    </div>

    <div class="cs-field-holder">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::label('email', 'Email Address', array('class' => 'control-label')) !!}
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::email('email', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'email')!!}
        </div>
    </div>

    <div class="cs-field-holder">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::label('password', 'Password', array('class' => 'control-label')) !!}
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::password('password', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'password')!!}
        </div>
    </div>

    <div class="cs-field-holder">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::label('password_confirmation', 'Repeat Password', array('class' => 'control-label')) !!}
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {!! Form::password('password_confirmation', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'password_confirmation')!!}
        </div>
    </div>

    @if(Settings::get('register_first_name'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('first_name', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'first_name')!!}
            </div>
        </div>
    @endif

    @if(Settings::get('register_last_name'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('last_name', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'last_name')!!}
            </div>
        </div>
    @endif

    @if(Settings::get('register_address'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('address', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'address')!!}
            </div>
        </div>
    @endif

    @if(Settings::get('register_address2'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('address2', 'Address 2', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('address2', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'address2')!!}
            </div>
        </div>
    @endif

    @if(Settings::get('register_city'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('city', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'city')!!}
            </div>
        </div>
    @endif

    @if(Settings::get('register_county'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('county', 'County', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('county', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'county')!!}
            </div>
        </div>
    @endif

    @if(Settings::get('register_postcode'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('postcode', 'Postcode', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('postcode', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'postcode')!!}
            </div>
        </div>
    @endif

    @if(Settings::get('register_country'))
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('country', 'Country', array('class' => 'control-label')) !!}
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {!! Form::text('country', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'country')!!}
            </div>
        </div>
    @endif

    <div class="cs-field-holder">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="cs-btn-submit">
                <input type="submit" value="Create Membership">
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>