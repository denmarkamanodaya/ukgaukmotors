@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{url('assets/js/emailer.js')}}"></script>
    <script>
        CKEDITOR.replace( 'content_html', {
            filebrowserBrowseUrl: ''+baseUrl+'/filemanager/index.html'
        } );
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Emailer' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Emailer</span> - Send an Email
@stop


@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{url('admin/emailer/archive')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-eye"></i></b> View Emails that have been sent</a>
            <br><br>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">User Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/emailer')) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('user', 'Select A User', array('class' => 'control-label')) !!}
                                {!! Form::select('user', $users, $userId, array('class' => 'form-control', 'id' => 'user', 'autocomplete' => 'off')) !!}
                                @if ($errors->has('status'))
                                    <span class="help-block validation-error-label" for="status">{{ $errors->first('user') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="required_roles">
                                {!! Form::label('roles', 'Or Send to a Role Group', array('class' => 'control-label')) !!}
                                @foreach($roles as $role)
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('roles[]', $role->id, null, array('id' => 'role', 'autocomplete' => 'off')) !!}
                                            {!! $role->title !!}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @if ($errors->has('roles'))
                                <span class="help-block validation-error-label" for="roles">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="required_roles">
                                {!! Form::label('roles', 'Or Send to Everyone', array('class' => 'control-label')) !!}
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('allUsers', '1', null, array('id' => 'allUsers', 'autocomplete' => 'off')) !!}
                                            All Users
                                        </label>
                                    </div>

                            </div>
                            @if ($errors->has('roles'))
                                <span class="help-block validation-error-label" for="roles">{{ $errors->first('roles') }}</span>
                            @endif
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

                    <div class="row">
                        <div class="col-md-12">

                            <button class="btn btn-primary btn-rounded btn-xs" data-target="#helptext_emails" data-toggle="modal" type="button">
                                Help
                                <i class="far fa-question position-right"></i>
                            </button>
                            <br><br>

                            <div class="form-group">
                                {!! Form::label('subject', 'Subject', array('class' => 'control-label')) !!}
                                {!! Form::text('subject', null, array('class' => 'form-control', 'autocomplete' => 'off', 'required')) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block validation-error-label" for="subject">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('content_html', 'Content (HTML)', array('class' => 'control-label')) !!}
                                {!! Form::textarea('content_html', null, array('class' => 'form-control', 'id' => 'content_html', 'autocomplete' => 'off', 'required')) !!}
                                @if ($errors->has('content_html'))
                                    <span class="help-block validation-error-label" for="content_html">{{ $errors->first('content_html') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Send Email<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        {!! Form::close() !!}
    </div>
    @include('base::members.help.page')
@stop


