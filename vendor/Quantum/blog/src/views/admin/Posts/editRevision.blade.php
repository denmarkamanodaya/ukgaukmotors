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

        $('#postDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this post?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Posts' => '/admin/posts', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Posts</span> - Edit post
@stop


@section('content')


    <div class="row">

        <div class="col-md-8">

            <div class="panel panel-default">

                <div class="panel-body bg-info">
                    <div class="col-md-8 mt-5"><span class="valign-middle">Showing Revision Created At : {{$postRevision->created_at->format('l jS \\of F Y h:i:s A')}}</span></div>
                    <div class="col-md-4"><a href="{!! url('admin/post/'.$post->slug) !!}"><button class="btn btn-default btn-xs" type="button"><i class="far fa-retweetposition-left"></i> Return To Original Post</button></a></div>

                </div>
            </div>

            <div id="accordion-control-right" class="panel-group panel-group-control panel-group-control-right content-group-lg">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">Post Details</a>
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">
                            {!! Form::model($post, array('method' => 'POST', 'url' => '/admin/post/'.$post->slug.'/update', 'autocomplete' => 'off', 'id' => 'PostManage', 'files' => true)) !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Post Title', array('class' => 'control-label')) !!}
                                        {!! Form::text('title', $postRevision->title, array('class' => 'form-control', 'required')) !!}
                                        {!!inputError($errors, 'title')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('slug', 'Post Slug', array('class' => 'control-label')) !!}
                                        {!! Form::text('slug', $postRevision->slug, array('class' => 'form-control', 'required')) !!}
                                        {!!inputError($errors, 'slug')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('content', 'Content', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('content', $postRevision->content, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'content')) !!}
                                        {!!inputError($errors, 'content')!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('summary', 'Summary', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('summary', $postRevision->summary, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'summary')) !!}
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
                                {!! Form::select('category', $categories,$post->post_category_id, array('class' => 'form-control')) !!}
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
                                <input type="text" class="form-control" id="tags" name="tags" value="{{tagInputFormat($post->tags)}}" />
                                <span class="help-block">Hit Enter after each tag.</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h6 class="text-semibold">Featured Image</h6>
                            <div class="form-group">
                                {!! Form::file('featured_image2') !!}
                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                {!!inputError($errors, 'featured_image2')!!}
                            </div>
                            @if($post->meta->featured_image != '')
                                <div class="thumbnail_featured_image2_wrap">
                                    <div class="row">
                                        <img id="thumbnail_featured_image2" class="" style="max-height:100px;" src="{{ featured_image($post->meta->featured_image) }}">
                                    </div>
                                    <div class="row">
                                        To remove the image please use the button found in the Meta section.
                                    </div>
                                </div>

                            @endif
                        </div>

                    </div>
                    <div class="row">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update Original Post<i class="far fa-save position-right"></i></button>
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Author</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-3">
                                {!! show_profile_pic($post->user, 'img-responsive') !!}
                        </div>
                        <div class="col-md-9">
                            <div class="row"><h4>{{$post->user->username}}</h4></div>
                            <div class="row">Post Created : {{$post->created_at->toDayDateTimeString()}}</div>
                            <div class="row">Updated On : {{$post->updated_at->toDayDateTimeString()}}</div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Remove Post</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                {!! Form::model($post,array('method' => 'POST', 'url' => '/admin/post/'. $post->slug .'/delete', 'id' => 'postDelete')) !!}
                                <button type="submit" class="btn btn-warning">Remove Post <i class="far fa-times position-right"></i></button>
                                {!! Form::close() !!}

                            </div>

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
                                    <div class="row"><a href="{{url('admin/post/'.$post->slug.'/revision/'.$revision->id)}}">Show Revision</a></div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif


        </div>

    </div>

    {!! Shortcode::showModal() !!}
@stop


