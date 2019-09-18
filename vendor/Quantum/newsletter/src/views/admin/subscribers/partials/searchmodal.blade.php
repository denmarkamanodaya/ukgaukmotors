<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Subscriber Search</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!! Form::open(array('method' => 'POST', 'url' => '/admin/newsletter/subscriber/search')) !!}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
                            {!! Form::text('first_name', null, array('class' => 'form-control', '', 'placeholder' => 'First Name')) !!}
                            @if ($errors->has('first_name'))
                                <span class="help-block validation-error-label" for="first_name">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
                            {!! Form::text('last_name', null, array('class' => 'form-control', '', 'placeholder' => 'Last Name')) !!}
                            @if ($errors->has('last_name'))
                                <span class="help-block validation-error-label" for="first_name">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::label('email', 'Email Address', array('class' => 'control-label')) !!}
                            {!! Form::text('email', null, array('class' => 'form-control', '')) !!}
                            @if ($errors->has('email'))
                                <span class="help-block validation-error-label" for="email">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::label('newsletter', 'Newsletter', array('class' => 'control-label')) !!}
                            {!! Form::select('newsletter', $newsletters, 0, array('class' => 'form-control', '')) !!}
                            @if ($errors->has('newsletter'))
                                <span class="help-block validation-error-label" for="transactionid">{{ $errors->first('newsletter') }}</span>
                            @endif
                        </div>

                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Search <i class="far fa-search position-right"></i></button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>





