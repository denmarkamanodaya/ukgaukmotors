@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/newsletter_email.js')}}"></script>
    <script type="text/javascript" src="{{url('theme/4/assets/js/plugins/pickers/anytime.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("#anytime-both").AnyTime_picker({
                format: "%M %D %Y %H:%i",
            });
        });
        $('#mailDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this mail shot?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', 'Mail' => '/admin/newsletter/mail', 'Edit Mail Shot' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Edit Mail Shot
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-flat border-top-info border-bottom-info">
                <div class="panel-heading">
                    <h6 class="panel-title">Preview</h6>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Once you have saved your work you can then preview the mailshot by hitting the below button.</p>
                            <div class="text-center"><a target="_blank" href="{{url('admin/newsletter/mail/'.$timedMail->id.'/preview')}}" class="btn bg-info btn-labeled ml-5" type="button"><b><i class="far fa-eye"></i></b> Preview</a></div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Email Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($timedMail, array('method' => 'POST', 'url' => '/admin/newsletter/mail/'.$timedMail->id.'/edit')) !!}

                    <div class="panel panel-flat border-top-info border-bottom-info">
                        <div class="panel-heading">
                            <h6 class="panel-title">Personalisation</h6>
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::checkbox('personalise', 1, null, array('id' => 'personalise')) !!}
                                        Add personalisation to this mail shot. IE user replacement fields?<br>If this newsletter has a large amount of subscribers then turning off personalisation will speed up sending.
                                        @if ($errors->has('personalise'))
                                            <span class="help-block validation-error-label" for="title">{{ $errors->first('personalise') }}</span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="row bcc-inputs">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2 mt-5">{!! Form::label('bcc_amount', 'Bcc Amount', array('class' => 'control-label')) !!}</div>
                                        <div class="col-md-2">{!! Form::number('bcc_amount', null, array('class' => 'form-control', 'min' => 0)) !!}
                                            @if ($errors->has('bcc_amount'))
                                                <span class="help-block validation-error-label" for="title">{{ $errors->first('bcc_amount') }}</span>
                                            @endif
                                        </div>
                                        <span class="col-md-12 help-block">If you turn off personalisation then we can batch send using bcc. Set the amount to be grouped together, 40 is a good number to choose.</span>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">{!! Form::label('bcc_to_email', 'Bcc Default To Address', array('class' => 'control-label')) !!}</div>
                                        <div class="col-md-12">{!! Form::email('bcc_to_email', null, array('class' => 'form-control', 'placeholder' => 'Leave blank for subscriber@')) !!}
                                            @if ($errors->has('bcc_to_email'))
                                                <span class="help-block validation-error-label" for="title">{{ $errors->first('bcc_to_email') }}</span>
                                            @endif
                                        </div>
                                        <span class="col-md-12 help-block">Using BCC we have to set an email address for the overall to address. So recipients will see this address in the to field instead of their email.<br>
                                        If left blank it will default to subscriber@[NewsletterFromDomain]</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('newsletter', 'Newsletter', array('class' => 'control-label')) !!}
                                {!! Form::select('newsletter', $newsletters, $timedMail->newsletter_id, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('newsletter') }}</span>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('subject', 'Subject', array('class' => 'control-label')) !!}
                                {!! Form::text('subject', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('subject') }}</span>
                                @endif
                                <span class="help-block">The email subject.</span>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                {!! Form::label('html_message', 'Html Message', array('class' => 'control-label')) !!}
                                <br><button class="btn btn-primary btn-rounded btn-xs" data-target="#helptext_emails" data-toggle="modal" type="button">
                                    Replacement Fields
                                    <i class="far fa-question position-right"></i>
                                </button><br><br>
                                {!! Form::textarea('html_message', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('html_message'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('html_message') }}</span>
                                @endif
                                <span class="help-block">The html message.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h5>Send Date</h5>
                        <p>Choose when to send the mail.</p>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                                <input type="text" value="{!! $timedMail->send_on->format('F jS Y H:i') !!}" name="send_on" id="anytime-both" class="form-control" readonly="">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-20">
                        <h5>Status</h5>
                        <p>All new mail is sent to pending so you can run a final check before is it actively added to the mail queue.<br>Once happy set this to Active.</p>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('active', 'Active', array('class' => 'control-label')) !!}
                                {!! Form::select('active', [1 => 'Active', 0 => 'Pending'],null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('active'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('subject') }}</span>
                                @endif

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Edit Newsletter Mail Shot<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}
    @include('newsletter::admin/email_help_noConfirm')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Delete Mail Shot</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/newsletter/mail/'.$timedMail->id.'/delete', 'id' => 'mailDelete')) !!}


                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning">Delete Newsletter Mail Shot<i class="far fa-times position-right"></i></button>
                        </div>

                    </div>

                    {!! Form::close() !!}
                </div>
            </div>


        </div>


    </div>

@stop


