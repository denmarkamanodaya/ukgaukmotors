@extends('admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Blog Settings' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Blog Settings</span> - Manage the registration form
@stop


@section('content')
    {!! Form::open(array('method' => 'POST', 'url' => '/admin/blog/settings', 'class' => 'form-horizontal', 'autocomplete' => 'off')) !!}

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">General Settings</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="enable_blog" value="1" id="enable_blog" @if( Settings::get('enable_blog') == 1) checked='checked' @endif>
                                    Enable Blog
                                    {!!inputError($errors, 'enable_blog')!!}
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <h5>Url Structure</h5>

                            <div class="form-group">
                                <label>
                                    <input type="radio" name="blog_link_structure" value="1" id="blog_link_structure" @if( Settings::get('blog_link_structure') == 1) checked='checked' @endif>
                                    Year/Month/Day/Title
                                    <span class="help-block" for="content">Ie: {{url('/post/'.date('Y').'/'.date('m').'/'.date('d').'/a-test-title')}}</span>
                                    {!!inputError($errors, 'blog_link_structure')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="radio" name="blog_link_structure" value="2" id="blog_link_structure" @if( Settings::get('blog_link_structure') == 2) checked='checked' @endif>
                                    Year/Month/Title
                                    <span class="help-block" for="content">Ie: {{url('/post/'.date('Y').'/'.date('m').'/a-test-title')}}</span>
                                    {!!inputError($errors, 'blog_link_structure')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="radio" name="blog_link_structure" value="3" id="blog_link_structure" @if( Settings::get('blog_link_structure') == 3) checked='checked' @endif>
                                    Year/Title
                                    <span class="help-block" for="content">Ie: {{url('/post/'.date('Y').'/a-test-title')}}</span>
                                    {!!inputError($errors, 'blog_link_structure')!!}
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="radio" name="blog_link_structure" value="4" id="blog_link_structure" @if( Settings::get('blog_link_structure') == 4) checked='checked' @endif>
                                    Title
                                    {!!inputError($errors, 'blog_link_structure')!!}
                                    <span class="help-block" for="content">Ie: {{url('/post/a-test-title')}}</span>
                                </label>
                            </div>



                        </div>
                    </div>



                </div>
            </div>


        </div>


    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Commit Settings</h6>
                </div>

                <div class="panel-body">
                    {!! Form::button('<i class="far fa-save"></i> Save Settings', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                </div>
            </div>

        </div>
    </div>

    {!! Form::close() !!}
@stop


