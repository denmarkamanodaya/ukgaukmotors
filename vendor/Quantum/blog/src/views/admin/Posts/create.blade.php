@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/posts.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/postsCreate.js')}}"></script>
    <script type="text/javascript" src="{{url('theme/4/assets/js/plugins/pickers/anytime.min.js')}}"></script>
    <script type="text/javascript" src="{{url('theme/4/assets/js/plugins/forms/tags/tagsinput.min.js')}}"></script>
    <script src="{{url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>

    <script>
        $(document).ready(function() {
            $("#anytime-both").AnyTime_picker({
                format: "%M %D %Y %H:%i",
            });
        });

        $('#tags').tagsinput({
            tagClass: function(item){
                return 'label label-success';
            }
        });
        $('#lfm').filemanager('image', {prefix: '/filemanager'});
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Posts' => '/admin/posts', 'Create' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Posts</span> - Create a new post
@stop


@section('content')


    <div class="row">
        <div class="col-md-8">

            <div id="accordion-control-right" class="panel-group panel-group-control panel-group-control-right content-group-lg">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">Post Details</a>
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">
                            {!! Form::open(array('method' => 'POST', 'url' => '/admin/post/create', 'autocomplete' => 'off', 'id' => 'PostManage', 'files' => true)) !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Post Title', array('class' => 'control-label')) !!}
                                        {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                                        {!!inputError($errors, 'title')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('slug', 'Post Slug', array('class' => 'control-label')) !!}
                                        {!! Form::text('slug', null, array('class' => 'form-control', 'required')) !!}
                                        {!!inputError($errors, 'slug')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('content', 'Content', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('content', null, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'content')) !!}
                                        {!!inputError($errors, 'content')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('summary', 'Summary', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('summary', null, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'summary')) !!}
                                        {!!inputError($errors, 'summary')!!}
                                        <span class="help-block">The summary is required and is displayed on the post listing pages. Limited to 100 words.</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @include('blog::admin.Posts.partials.meta')
            </div>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Post Extra</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                {!! Form::label('status', 'Post Status', array('class' => 'control-label')) !!}
                                {!! Form::select('status', array('published' => 'Published', 'unpublished' => 'Unpublished'),null, array('class' => 'form-control')) !!}
                                    {!!inputError($errors, 'status')!!}
                                    </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                {!! Form::label('area', 'Post Visability', array('class' => 'control-label')) !!}
                                {!! Form::select('area', array('public' => 'Public', 'members' => 'Members'),null, array('class' => 'form-control', 'id' => 'post-area')) !!}
                                    {!!inputError($errors, 'area')!!}
                                    </div>

                                <div class="form-group" id="required_roles">
                                {!! Form::label('roles', 'Required Roles', array('class' => 'control-label')) !!}
                                @foreach($roles as $role)
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('roles[]', $role->id,null, array('id' => 'check-'.$role->name)) !!}
                                                {!! $role->title !!}
                                            </label>
                                        </div>
                                @endforeach
                                    </div>
                                {!!inputError($errors, 'roles')!!}

                            </div>

                            <div class="col-md-12">
                                <div class="content-group">
                                    <h6 class="text-semibold">Publish Date</h6>
                                    <div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::checkbox('publishOnTime', 1,null, array('id' => 'publishOnTime')) !!}
                                                    Publish this post at a set time ?
                                                </label>
                                            </div>

                                    </div>
                                    <div id="publishTimeSelect">
                                        <p>Select the date and time to publish this post</p>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                                            <input type="text" value="{!! date('F jS Y H:i') !!}" name="publishDateTime" id="anytime-both" class="form-control" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-semibold">Post Category</h6>
                                <div class="form-group">
                                    {!! Form::label('category', 'Post Category', array('class' => 'control-label')) !!}
                                    {!! Form::select('category', $categories,$defaultCategory, array('class' => 'form-control')) !!}
                                    {!!inputError($errors, 'status')!!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-semibold">Post Enhancement</h6>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('sticky', 1,null, array('id' => 'sticky')) !!}
                                            Make Post Sticky ?
                                        </label>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('featured', 1,null, array('id' => 'featured')) !!}
                                            Set as a Featured Post ?
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-semibold">Post Tags</h6>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="tags" name="tags" value="" />
                                    <span class="help-block">Hit Enter after each tag.</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-semibold">Featured Image</h6>
                                <div class="form-group">
                                    <input name="featured_image2" type="file">
                                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>

                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save Post<i class="far fa-save position-right"></i></button>
                            </div>

                        </div>

                </div>
            </div>

        </div>

    </div>
    {!! Form::close() !!}
    {!! Shortcode::showModal() !!}
@stop


