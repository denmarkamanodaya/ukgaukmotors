<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <a href="#accordion-control-right-group3" data-parent="#accordion-control-right" data-toggle="collapse" class="collapsed">Page Meta</a>
        </h6>
    </div>
    <div class="panel-collapse collapse" id="accordion-control-right-group3">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('featured_image', 'Featured Image', array('class' => 'control-label')) !!}
                        <button type="button" class="btn btn-primary mb-5" id="lfm" data-input="featured_image" data-preview="thumbnail_featured_image">Choose Image <i class="far fa-image position-right"></i></button>
                        {!! Form::text('featured_image', isset($page->meta->featured_image)? $page->meta->featured_image : '', array('class' => 'form-control')) !!}
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
                        {!! Form::label('description', 'Description', array('class' => 'control-label')) !!}
                        {!! Form::text('description', isset($page->meta->description)? $page->meta->description : '', array('class' => 'form-control')) !!}
                        @if ($errors->has('description'))
                            <span class="help-block" for="description">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('keywords', 'Keywords', array('class' => 'control-label')) !!}
                        {!! Form::text('keywords', isset($page->meta->keywords)? $page->meta->keywords : '', array('class' => 'form-control')) !!}
                        @if ($errors->has('keywords'))
                            <span class="help-block" for="keywords">{{ $errors->first('keywords') }}</span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('meta_title', 'Title', array('class' => 'control-label')) !!}
                        {!! Form::text('meta_title', isset($page->meta->meta_title) ? $page->meta->meta_title : '', array('class' => 'form-control')) !!}
                        @if ($errors->has('meta_title'))
                            <span class="help-block" for="title">{{ $errors->first('meta_title') }}</span>
                        @endif
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type', 'Type', array('class' => 'control-label')) !!}
                        {!! Form::select('type', ['website' => 'Website', 'article' => 'Article'], isset($page->meta->type)? $page->meta->type : '', array('class' => 'form-control')) !!}
                        @if ($errors->has('type'))
                            <span class="help-block" for="type">{{ $errors->first('type') }}</span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('robots', 'Robots', array('class' => 'control-label')) !!}
                        {!! Form::select('robots', ['index' => 'index', 'index, follow' => 'index, follow', 'index, nofollow' => 'index, nofollow', 'noindex' => 'noindex', 'noindex, nofollow' => 'noindex, nofollow', 'noindex, follow' => 'noindex, follow'], isset($page->meta->robots)? $page->meta->robots : 'index, follow', array('class' => 'form-control')) !!}
                        @if ($errors->has('robots'))
                            <span class="help-block" for="robots">{{ $errors->first('robots') }}</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
