<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <a href="#accordion-control-right-group3" data-parent="#accordion-control-right" data-toggle="collapse" class="collapsed">Post Meta</a>
        </h6>
    </div>
    <div class="panel-collapse collapse" id="accordion-control-right-group3">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('featured_image', 'Featured Image', array('class' => 'control-label')) !!}
                        <button type="button" class="btn btn-primary" onclick="BrowseServer('featured_image');">Choose Image <i class="far fa-image position-right"></i></button>
                        {!! Form::hidden('featured_image', isset($post->meta->featured_image)? $post->meta->featured_image : '', array('class' => 'form-control')) !!}
                        @if ($errors->has('featured_image'))
                            <span class="help-block" for="featured_image">{{ $errors->first('featured_image') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row" id="featured_image_preview_wrap">
                            <div class="col-md-6 text-center">Thumbnail Preview<div id="thumbnail_featured_image" class=""></div></div>
                            <div class="col-md-6 text-center"><button type="button" class="btn btn-warning" id="featured_image_remove">Remove Image <i class="far fa-times position-right"></i></button></div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('description', 'Description', array('class' => 'control-label')) !!}
                        {!! Form::text('description', isset($post->meta->description)? $post->meta->description : '', array('class' => 'form-control')) !!}
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
                        {!! Form::text('keywords', isset($post->meta->keywords)? $post->meta->keywords : '', array('class' => 'form-control')) !!}
                        @if ($errors->has('keywords'))
                            <span class="help-block" for="keywords">{{ $errors->first('keywords') }}</span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type', 'Type', array('class' => 'control-label')) !!}
                        {!! Form::select('type', ['website' => 'Website', 'article' => 'Article'], isset($post->meta->type)? $post->meta->type : '', array('class' => 'form-control')) !!}
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
                        {!! Form::select('robots', ['index' => 'index', 'index, follow' => 'index, follow', 'index, nofollow' => 'index, nofollow', 'noindex' => 'noindex', 'noindex, nofollow' => 'noindex, nofollow', 'noindex, follow' => 'noindex, follow'], isset($post->meta->robots)? $post->meta->robots : 'index, follow', array('class' => 'form-control')) !!}
                        @if ($errors->has('robots'))
                            <span class="help-block" for="robots">{{ $errors->first('robots') }}</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>