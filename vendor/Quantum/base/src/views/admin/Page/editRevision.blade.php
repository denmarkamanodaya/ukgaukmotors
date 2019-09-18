@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/page.js')}}"></script>
    <script type="text/javascript" src="{{url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>
        $('#lfm').filemanager('image', {prefix: '/filemanager'});
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Page' => '/admin/pages', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Pages</span> - Edit a page
@stop


@section('content')


    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">

                <div class="panel-body bg-info">
                    <div class="col-md-8 mt-5"><span class="valign-middle">Showing Revision Created At : {{$pageRevision->created_at->format('l jS \\of F Y h:i:s A')}}</span></div>
                    <div class="col-md-4"><a href="{!! url('admin/page/'.$page->id.'/edit') !!}"><button class="btn btn-default btn-xs" type="button"><i class="far fa-retweet position-left"></i> Return To Original Page</button></a></div>

                </div>
            </div>

            <div id="accordion-control-right" class="panel-group panel-group-control panel-group-control-right content-group-lg">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">Page Details</a>
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">
                            {!! Form::model($page,array('method' => 'POST', 'url' => '/admin/page/'.$page->id.'/update')) !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Page Title', array('class' => 'control-label')) !!}
                                        {!! Form::text('title', $pageRevision->title, array('class' => 'form-control', 'required')) !!}
                                        @if ($errors->has('title'))
                                            <span class="help-block validation-error-label" for="title">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        Page Route :
                                    </div>
                                    <div class="col-md-3 text-right">
                                        {!! ENV('APP_URL') !!}<span id="route-area"></span>
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::text('route', $pageRevision->route, array('class' => 'form-control', 'id' => 'route', 'required')) !!}
                                        @if ($errors->has('route'))
                                            <span class="help-block validation-error-label" for="route">{{ $errors->first('route') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('subtitle', 'Page SubTitle', array('class' => 'control-label')) !!}
                                        {!! Form::text('subtitle', $pageRevision->subTitle, array('class' => 'form-control')) !!}
                                        @if ($errors->has('subtitle'))
                                            <span class="help-block validation-error-label" for="subtitle">{{ $errors->first('subtitle') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('preContent', 'Header Content', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('preContent', $pageRevision->preContent, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'preContent')) !!}
                                        <span class="help-block" for="content">Header content is optional and dependent upon theme if content is displayed</span>
                                        @if ($errors->has('preContent'))
                                            <span class="help-block validation-error-label" for="content">{{ $errors->first('preContent') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('content', 'Main Content', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('content', $pageRevision->content, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'content')) !!}
                                        @if ($errors->has('content'))
                                            <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('base::admin.Page.partials.css-js')
                @include('base::admin.Page.partials.meta')
            </div>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Page Extra</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('status', 'Page Status', array('class' => 'control-label')) !!}
                                {!! Form::select('status', array('published' => 'Published', 'unpublished' => 'Unpublished'),null, array('class' => 'form-control')) !!}
                                @if ($errors->has('status'))
                                    <span class="help-block validation-error-label" for="status">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('area', 'Page Area', array('class' => 'control-label')) !!}
                                {!! Form::select('area', array('public' => 'Public', 'members' => 'Members', 'admin' => 'Admin'),null, array('class' => 'form-control', 'id' => 'page-area')) !!}
                                @if ($errors->has('area'))
                                    <span class="help-block validation-error-label" for="area">{{ $errors->first('area') }}</span>
                                @endif
                            </div>

                            <div class="form-group" id="required_roles">
                                {!! Form::label('roles', 'Required Roles', array('class' => 'control-label')) !!}
                                @foreach($roles as $role)
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('roles[]', $role->id, in_array($role->id, $selectedRoles), array('id' => 'check-'.$role->name)) !!}
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
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update Original Page<i class="far fa-save position-right"></i></button>
                        </div>

                    </div>



                </div>
            </div>

            @if($revisions->count() > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Revisions</h6>
                        <div class="heading-elements">

                        </div>
                        <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                    <div class="panel-body">
                        <p>Showing Latest First</p>
                        @foreach($revisions as $revision)
                            <div class="row mb-10">
                                <div class="col-md-6">
                                    <div class="row">{{$revision->created_at->format('l jS \\of F Y h:i:s A')}}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row"><a href="{{url('admin/page/'.$page->id.'/revision/'.$revision->id)}}">Show Revision</a></div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif

        </div>

    </div>
    {!! Form::close() !!}
    {!! Shortcode::showModal() !!}
@stop


