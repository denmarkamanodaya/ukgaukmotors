
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a href="#accordion-control-right-group2" data-parent="#accordion-control-right" data-toggle="collapse" class="collapsed" aria-expanded="false">Page Css - Js</a>
            </h6>
        </div>
        <div class="panel-collapse collapse" id="accordion-control-right-group2" aria-expanded="false">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('bodyClass', 'Body Class', array('class' => 'control-label')) !!}
                            {!! Form::text('bodyClass', isset($book->bodyClass)? $book->bodyClass : '', array('class' => 'form-control')) !!}
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
                            {!! Form::textarea('pageCss', isset($book->pageCss)? $book->pageCss : '', array('class' => 'form-control')) !!}
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
                            {!! Form::textarea('pageJs', isset($book->pageJs)? $book->pageJs : '', array('class' => 'form-control')) !!}
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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('hideMenu', false) !!}
                                    {!! Form::checkbox('hideMenu', 1, null, array('class' => 'styled')) !!} Hide The Menu
                                </label>
                            </div>
                            @if ($errors->has('hideMenu'))
                                <span class="help-block" for="hideMenu">{{ $errors->first('hideMenu') }}</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>