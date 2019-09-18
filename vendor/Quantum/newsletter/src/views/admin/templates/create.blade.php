@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'content', {
            filebrowserImageBrowseUrl: '/filemanager?type=Images',
            filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/filemanager?type=Files',
            filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
            uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
            enterMode : CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            extraAllowedContent: 'style;*[id,rel](*){*}',
            disableNativeSpellChecker: false,
            scayt_autoStartup: true,
            height: 1000
        } );
        CKEDITOR.dtd.$removeEmpty['i'] = false;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletter' => '/admin/newsletter', 'Templates' => '/admin/newsletter/templates', 'Create' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Create a new Template
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Template Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/newsletter/template/create')) !!}
                    {!! Form::hidden('template_type', 'template') !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Template Title', array('class' => 'control-label')) !!}
                                {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('title'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('title') }}</span>
                                @endif
                                <span class="help-block">Name your template.</span>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('content', 'Template Content', array('class' => 'control-label')) !!}
                                {!! Form::textarea('content', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('content'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('content') }}</span>
                                @endif
                                <span class="help-block">The email html template.</span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Create Newsletter Template<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </div>
    {!! Form::close() !!}
    @include('newsletter::admin/email_help')
    {!! Shortcode::showModal() !!}

@stop


