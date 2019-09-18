@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{ url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/book.js')}}"></script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Books' => '/admin/books', 'Book Chapters' => '/admin/book/'.$book->id.'/manage', 'Chapter' => '/admin/book/'.$book->id.'/chapter/'.$chapter->id.'/pages','Edit Page' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Book</span> - Edit page
@stop


@section('content')
    <div class="row">
        <div class="col-md-12 text-center mb-20">
            @if($book->front_cover != '')
                <img src="{!! url($book->front_cover) !!}" id="" class="" style="max-height:200px;">
            @endif
            @if($book->back_cover != '')
                <img src="{!! url($book->back_cover) !!}" id="" class="ml-10" style="max-height:200px;">
            @endif
        </div>
        <div class="col-md-12 text-center mb-20"><h3>{{$chapter->title}}</h3></div>
    </div>

    <div class="row">
        <div class="col-md-9">

            <div id="accordion-control-right" class="panel-group panel-group-control panel-group-control-right content-group-lg">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">Page Details</a>
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">
                            {!! Form::model($page, array('method' => 'POST', 'url' => '/admin/book/'.$book->id.'/chapter/'.$chapter->id.'/page/'.$page->id.'/edit', 'id' => 'PageManage', 'files' => true, 'autocomplete' => 'off')) !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Page Title', array('class' => 'control-label')) !!}
                                        {!! Form::text('title', null, array('class' => 'form-control', 'required')) !!}
                                        @if ($errors->has('title'))
                                            <span class="help-block validation-error-label" for="title">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('featured_image', 'Featured Image', array('class' => 'control-label')) !!}
                                        <button type="button" class="btn btn-primary mb-5" id="lfm" data-input="featured_image" data-preview="thumbnail_featured_image">Choose Image <i class="far fa-image position-right"></i></button>
                                        {!! Form::text('featured_image', isset($book->meta->featured_image)? $book->meta->featured_image : '', array('class' => 'form-control')) !!}
                                        @if ($errors->has('featured_image'))
                                            <span class="help-block" for="featured_image">{{ $errors->first('featured_image') }}</span>
                                        @endif


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row" id="featured_image_preview_wrap">
                                            <div class="col-md-6 text-center"><img id="thumbnail_featured_image" class="" style="max-height:100px;"></div>
                                            <div class="col-md-6 text-center"><button type="button" class="btn btn-warning" id="featured_image_remove">Remove Image <i class="far fa-times position-right"></i></button></div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('content', 'Content', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('content', null, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'content')) !!}
                                        @if ($errors->has('content'))
                                            <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Page Extra</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="row">
                    <div class="form-group">
                        {!! Form::label('public_view', 'Premium only access', array('class' => 'control-label')) !!}
                        {!! Form::select('public_view', array('0' => 'Yes', '1' => 'No'),null, array('class' => 'form-control', 'id' => 'public_view')) !!}
                        @if ($errors->has('public_view'))
                            <span class="help-block validation-error-label" for="area">{{ $errors->first('public_view') }}</span>
                        @endif
                    </div>
                    </div>


                        <div class="row">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Update Page<i class="far fa-save position-right"></i></button>
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
                                <div class="col-md-8">
                                    <div class="row">{{$revision->created_at->format('l jS \\of F Y h:i:s A')}}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row"><a href="{{ url('admin/book/'.$book->id.'/chapter/'.$chapter->id.'/page/'.$page->id.'/revision/'.$revision->id)}}">Show Revision</a></div>
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


