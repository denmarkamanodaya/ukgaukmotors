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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Books' => '/admin/books', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Books</span> - Edit book
@stop


@section('content')


    <div class="row">
        <div class="col-md-8">

            <div id="accordion-control-right" class="panel-group panel-group-control panel-group-control-right content-group-lg">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a href="#accordion-control-right-group1" data-parent="#accordion-control-right" data-toggle="collapse">Book Details</a>
                        </h6>
                    </div>
                    <div class="panel-collapse collapse in" id="accordion-control-right-group1">
                        <div class="panel-body">
                            {!! Form::model($book, array('method' => 'POST', 'url' => '/admin/book/'.$book->id.'/edit', 'id' => 'PageManage', 'files' => true, 'autocomplete' => 'off')) !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Book Title', array('class' => 'control-label')) !!}
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
                                        {!! Form::label('front_cover', 'Front Cover Image', array('class' => 'control-label')) !!}
                                        <button type="button" class="btn btn-primary mb-5" id="lfm2" data-input="front_cover" data-preview="thumbnail_front_cover">Choose Image <i class="far fa-image position-right"></i></button>
                                        {!! Form::text('front_cover', null, array('class' => 'form-control')) !!}
                                        @if ($errors->has('front_cover'))
                                            <span class="help-block" for="front_cover">{{ $errors->first('front_cover') }}</span>
                                        @endif


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row" id="front_cover_preview_wrap">
                                            <div class="col-md-6 text-center"><img id="thumbnail_front_cover" class="" style="max-height:100px;"></div>
                                            <div class="col-md-6 text-center"><button type="button" class="btn btn-warning" id="front_cover_remove">Remove Image <i class="far fa-times position-right"></i></button></div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('back_cover', 'Rear Cover Image', array('class' => 'control-label')) !!}
                                        <button type="button" class="btn btn-primary mb-5" id="lfm3" data-input="back_cover" data-preview="thumbnail_back_cover">Choose Image <i class="far fa-image position-right"></i></button>
                                        {!! Form::text('back_cover', null, array('class' => 'form-control')) !!}
                                        @if ($errors->has('back_cover'))
                                            <span class="help-block" for="back_cover">{{ $errors->first('back_cover') }}</span>
                                        @endif


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row" id="back_cover_preview_wrap">
                                            <div class="col-md-6 text-center"><img id="thumbnail_back_cover" class="" style="max-height:100px;"></div>
                                            <div class="col-md-6 text-center"><button type="button" class="btn btn-warning" id="back_cover_remove">Remove Image <i class="far fa-times position-right"></i></button></div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('preContent', 'Header Content', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('preContent', null, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'preContent')) !!}
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
                                        {!! Form::label('content', 'Book Summary', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('content', null, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'content')) !!}
                                        @if ($errors->has('content'))
                                            <span class="help-block validation-error-label" for="content">{{ $errors->first('content') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('details', 'Book Details Page', array('class' => 'control-label', 'required')) !!}
                                        <br>{!! Shortcode::showButton() !!}<br><br>
                                        {!! Form::textarea('details', null, array('class' => 'form-control cke_1 cke cke_reset cke_chrome cke_editor_editor-full cke_ltr cke_browser_gecko', 'id' => 'details')) !!}
                                        @if ($errors->has('details'))
                                            <span class="help-block validation-error-label" for="content">{{ $errors->first('details') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">Important : Dont forget to set the featured image in the meta section below.<br> Any page that doesn't have an image set will default to this featured image.</div>
                            </div>


                        </div>
                    </div>
                </div>
                @include('admin.Book.partials.css-js')
                @include('admin.Book.partials.meta')
            </div>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Book Extra</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                        <div class="row">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Edit Book<i class="far fa-save position-right"></i></button>
                            </div>

                        </div>

                </div>
            </div>

        </div>

    </div>
    {!! Form::close() !!}
    {!! Shortcode::showModal() !!}
@stop


