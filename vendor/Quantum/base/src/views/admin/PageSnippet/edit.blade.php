@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/news.js')}}"></script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Page Snippet' => '/admin/pagesnippet', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Page Snippet</span> - Edit a snippet
@stop


@section('content')


    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Snippet Details</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($newsItem,array('method' => 'POST', 'url' => '/admin/pagesnippet/snippet/'.$newsItem->id.'/update')) !!}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('title', 'Snippet Title', array('class' => 'control-label')) !!}
                                {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                                @if ($errors->has('title'))
                                    <span class="help-block validation-error-label" for="title">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('header_content', 'Header Content', array('class' => 'control-label')) !!}
                                <button class="btn btn-primary btn-rounded btn-xs" data-target="#helptext_news_content" data-toggle="modal" type="button">
                                    Help
                                    <i class="far fa-question position-right"></i>
                                </button><br><br>
                                {!! Form::textarea('header_content', null, array('class' => 'form-control', 'id' => 'header_content')) !!}
                                @if ($errors->has('header_content'))
                                    <span class="help-block validation-error-label" for="content">{{ $errors->first('header_content') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('content', 'Main Content', array('class' => 'control-label')) !!}
                                <button class="btn btn-primary btn-rounded btn-xs" data-target="#helptext_news_content" data-toggle="modal" type="button">
                                    Help
                                    <i class="far fa-question position-right"></i>
                                </button><br><br>
                                {!! Form::textarea('content', null, array('class' => 'form-control', 'id' => 'content', 'required')) !!}
                                @if ($errors->has('content'))
                                    <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Snippet Extra</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('status', 'Snippet Status', array('class' => 'control-label')) !!}
                                {!! Form::select('status', array('published' => 'Published', 'unpublished' => 'Unpublished'),null, array('class' => 'form-control')) !!}
                                @if ($errors->has('status'))
                                    <span class="help-block validation-error-label" for="status">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('area', 'Snippet Area', array('class' => 'control-label')) !!}
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


