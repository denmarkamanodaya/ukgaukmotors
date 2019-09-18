
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a href="#accordion-control-right-group2" data-parent="#accordion-control-right" data-toggle="collapse" class="collapsed" aria-expanded="false">Post Css - Js</a>
            </h6>
        </div>
        <div class="panel-collapse collapse" id="accordion-control-right-group2" aria-expanded="false">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('bodyClass', 'Body Class', array('class' => 'control-label')) !!}
                            {!! Form::text('bodyClass', isset($page->bodyClass)? $page->bodyClass : '', array('class' => 'form-control')) !!}
                            @if ($errors->has('bodyClass'))
                                <span class="help-block" for="bodyClass">{{ $errors->first('bodyClass') }}</span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('pageCss', 'Page Css', array('class' => 'control-label')) !!}
                            {!! Form::textarea('pageCss', isset($page->pageCss)? $page->pageCss : '', array('class' => 'form-control')) !!}
                            @if ($errors->has('pageCss'))
                                <span class="help-block" for="pageCss">{{ $errors->first('pageCss') }}</span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('pageJs', 'Page Js', array('class' => 'control-label')) !!}
                            {!! Form::textarea('pageJs', isset($page->pageJs)? $page->pageJs : '', array('class' => 'form-control')) !!}
                            @if ($errors->has('pageJs'))
                                <span class="help-block" for="pageJs">{{ $errors->first('pageJs') }}</span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('showBreadcrumbs', false) !!}
                                    {!! Form::checkbox('showBreadcrumbs', 1, null, array('class' => 'styled')) !!} Show Breadcrumbs
                                </label>
                            </div>
                            @if ($errors->has('showBreadcrumbs'))
                                <span class="help-block" for="showBreadcrumbs">{{ $errors->first('showBreadcrumbs') }}</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>