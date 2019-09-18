<div class="register-form">
{!! Form::open(array('method' => 'POST', 'url' => 'register', 'autocomplete' => 'off')) !!}
<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            {!! Form::label('username', 'Username', array('class' => 'control-label')) !!}
            {!! Form::text('username', null, array('class' => 'form-control', 'required')) !!}
            @if ($errors->has('username'))
                <span class="help-block validation-error-label" for="username">{{ $errors->first('username') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            {!! Form::label('email', 'Email Address', array('class' => 'control-label')) !!}
            {!! Form::email('email', null, array('class' => 'form-control', 'required')) !!}
            @if ($errors->has('email'))
                <span class="help-block validation-error-label" for="email">{{ $errors->first('email') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            {!! Form::label('password', 'Password', array('class' => 'control-label')) !!}
            {!! Form::password('password', array('class' => 'form-control', 'required')) !!}
            @if ($errors->has('password'))
                <span class="help-block validation-error-label" for="password">{{ $errors->first('password') }}</span>
            @endif
        </div>
    </div>
</div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                {!! Form::label('password_confirmation', 'Repeat Password', array('class' => 'control-label')) !!}
                {!! Form::password('password_confirmation', array('class' => 'form-control', 'required')) !!}
                @if ($errors->has('password_confirmation'))
                    <span class="help-block validation-error-label" for="password_confirmation">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>
        </div>
    </div>

    @if(Settings::get('register_first_name'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
                    {!! Form::text('first_name', null, array('class' => 'form-control', 'required', 'placeholder' => 'First Name')) !!}
                    @if ($errors->has('first_name'))
                        <span class="help-block validation-error-label" for="first_name">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('register_last_name'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
                    {!! Form::text('last_name', null, array('class' => 'form-control', 'required', 'placeholder' => 'Last Name')) !!}
                    @if ($errors->has('last_name'))
                        <span class="help-block validation-error-label" for="first_name">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('register_address'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
                    {!! Form::text('address', null, array('class' => 'form-control')) !!}
                    @if ($errors->has('address'))
                        <span class="help-block validation-error-label" for="address">{{ $errors->first('address') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('register_address2'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('address2', 'Address 2', array('class' => 'control-label')) !!}
                    {!! Form::text('address2', null, array('class' => 'form-control')) !!}
                    @if ($errors->has('address2'))
                        <span class="help-block validation-error-label" for="address2">{{ $errors->first('address2') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('register_city'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
                    {!! Form::text('city', null, array('class' => 'form-control')) !!}
                    @if ($errors->has('city'))
                        <span class="help-block validation-error-label" for="city">{{ $errors->first('city') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('register_county'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('county', 'County', array('class' => 'control-label')) !!}
                    {!! Form::text('county', null, array('class' => 'form-control')) !!}
                    @if ($errors->has('county'))
                        <span class="help-block validation-error-label" for="county">{{ $errors->first('county') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('register_postcode'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('postcode', 'Postcode', array('class' => 'control-label')) !!}
                    {!! Form::text('postcode', null, array('class' => 'form-control')) !!}
                    @if ($errors->has('postcode'))
                        <span class="help-block validation-error-label" for="postcode">{{ $errors->first('postcode') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('register_country'))
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('country', 'Country', array('class' => 'control-label')) !!}
                    {!! Form::select('country', country_array(), 'GBR', ['class' => 'form-control']) !!}
                    @if ($errors->has('country'))
                        <span class="help-block validation-error-label" for="country">{{ $errors->first('country') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(Settings::get('recaptcha_register') && Settings::get('recaptcha_site_key') != '')
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <div style="margin-left: -12px;" class="form-group g-recaptcha" data-sitekey="{!! Settings::get('recaptcha_site_key') !!}"></div>
    @endif

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Create Membership <i class="far fa-user position-right"></i></button>
    </div>
    {!! Form::close() !!}
</div>