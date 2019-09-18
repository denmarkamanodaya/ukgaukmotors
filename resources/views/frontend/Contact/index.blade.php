 <div class="row">
        <div class="col-md-12">
        <!-- Simple panel -->
    <div class="panel panel-flat">
        <div class="panel-body">
            <h1>Contact Us</h1>
            <p>To contact us please fill in the below form and we will get back to you as soon as possible</p>

            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/contact')) !!}

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                {!! Form::label('name', 'Name', array('class' => 'control-label')) !!}
                                {!! Form::text('name', null, array('class' => 'form-control', 'required', 'placeholder' => 'Your Name')) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block validation-error-label" for="name">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                {!! Form::label('email', 'Email', array('class' => 'control-label')) !!}
                                {!! Form::email('email', null, array('class' => 'form-control', 'required', 'placeholder' => 'Your Email Address')) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block validation-error-label" for="email">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                {!! Form::label('subject', 'Subject', array('class' => 'control-label')) !!}
                                {!! Form::text('subject', null, array('class' => 'form-control', 'required', 'placeholder' => 'Your Subject')) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block validation-error-label" for="subject">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                {!! Form::label('message', 'Message', array('class' => 'control-label')) !!}
                                {!! Form::textarea('message', null, array('class' => 'form-control', 'type' => 'email', 'required', 'placeholder' => 'Your Message')) !!}
                                @if ($errors->has('message'))
                                    <span class="help-block validation-error-label" for="message">{{ $errors->first('message') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Send Message<i class="far fa-envelope position-right"></i></button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /simple panel -->
        </div>
    </div>


