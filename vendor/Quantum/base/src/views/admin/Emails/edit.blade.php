@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'content_html', {
            filebrowserImageBrowseUrl: '/filemanager?type=Images',
            filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/filemanager?type=Files',
            filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
            uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
            height: 1000
        } );
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Email' => '/admin/emails', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">System Email Edit</span> - {!! $email->title !!}
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Email Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($email,array('method' => 'POST', 'url' => '/admin/email/'.$email->id.'/update')) !!}
                    <button class="btn btn-primary btn-rounded btn-xs" data-target="#helptext_emails" data-toggle="modal" type="button">
                        Help
                        <i class="far fa-question position-right"></i>
                    </button>
<br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('subject', 'Subject', array('class' => 'control-label')) !!}
                                {!! Form::text('subject', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block" for="subject">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('content_html', 'Content (HTML)', array('class' => 'control-label')) !!}
                                {!! Form::textarea('content_html', null, array('class' => 'form-control', 'id' => 'content_html', 'required')) !!}
                                @if ($errors->has('content_html'))
                                    <span class="help-block" for="content_html">{{ $errors->first('content_html') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('content_text', 'Content (Text) - Optional', array('class' => 'control-label')) !!}
                                {!! Form::textarea('content_text', null, array('class' => 'form-control', 'id' => 'content_text')) !!}
                                @if ($errors->has('content_text'))
                                    <span class="help-block" for="content_html">{{ $errors->first('content_text') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Save Changes<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}
    @include('base::members.help.page')
@stop


