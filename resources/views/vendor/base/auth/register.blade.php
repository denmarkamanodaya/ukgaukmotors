<div class="register-form cs-register">
    {!! Form::open(array('method' => 'POST', 'url' => 'register', 'autocomplete' => 'off')) !!}

    
    <div class="cs-field-holder row">
        @if(Settings::get('register_first_name'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
                {!! Form::text('first_name', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'first_name')!!}
            </div>
        @endif

        @if(Settings::get('register_last_name'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
                {!! Form::text('last_name', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'last_name')!!}
            </div>
        @endif
    </div>
    

    <div class="cs-field-holder row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            {!! Form::label('username', 'Username', array('class' => 'control-label')) !!}
            {!! Form::text('username', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'username')!!}
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            {!! Form::label('email', 'Email Address', array('class' => 'control-label')) !!}
            {!! Form::email('email', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'email')!!}
        </div>
    </div>

    <div class="cs-field-holder row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            {!! Form::label('password', 'Password', array('class' => 'control-label')) !!}
            {!! Form::password('password', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'password')!!}
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            {!! Form::label('password_confirmation', 'Confirm Password', array('class' => 'control-label')) !!}
            {!! Form::password('password_confirmation', null, array('class' => 'form-control', 'required')) !!}
            {!!inputError($errors, 'password_confirmation')!!}
        </div>
    </div>

    <div class="cs-field-holder row">
        @if(Settings::get('register_address'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
                {!! Form::text('address', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'address')!!}
            </div>
        @endif

        @if(Settings::get('register_address2'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('address2', 'Address 2', array('class' => 'control-label')) !!}
                {!! Form::text('address2', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'address2')!!}
            </div>
        @endif
    </div>

    <div class="cs-field-holder row">
        @if(Settings::get('register_city'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
                {!! Form::text('city', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'city')!!}
            </div>
        @endif

        @if(Settings::get('register_county'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('county', 'County', array('class' => 'control-label')) !!}
                {!! Form::text('county', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'county')!!}
            </div>
        @endif
    </div>

    <div class="cs-field-holder row">
        @if(Settings::get('register_postcode'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('postcode', 'Postcode', array('class' => 'control-label')) !!}
                {!! Form::text('postcode', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'postcode')!!}
            </div>
        @endif

        @if(Settings::get('register_country'))
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                {!! Form::label('country', 'Country', array('class' => 'control-label')) !!}
                {!! Form::text('country', null, array('class' => 'form-control', 'required')) !!}
                {!!inputError($errors, 'country')!!}
            </div>
        @endif
    </div>

    @if(Settings::get('recaptcha_register') && Settings::get('recaptcha_site_key') != '')
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <div class="cs-field-holder">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div style="" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
            </div>
        </div>
    @endif

    <div class="cs-field-holder">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="btn-submit">
                <input class="btn btn-primary btn-lg btn-block" type="submit" value="Set Up Your Free Account Now!">
                <span class="fa-stack fa-sm">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-play fa-stack-1x"></i>
                </span>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>