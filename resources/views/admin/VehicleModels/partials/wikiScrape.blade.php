<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title">Wikipedia Scrape</h6>
                <div class="heading-elements">

                </div>
                <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

            <div class="panel-body">
                <p>To fill the description with content pulled in from wikipedia use the below form to enter the wikipedia page title.<br><br>
                IE: for page https://en.wikipedia.org/wiki/Ford_Escort_(Europe)<br>Enter : Ford_Escort_(Europe)
                </p>

                {!! Form::open(array('method' => 'POST', 'url' => '/admin/vehicle-model/'.$vehicleModel->id.'/description/addFromWiki', 'id' => 'WikiAdd')) !!}


                <div class="row">
                    <div class="col-md-1 col-sm-12">
                        {!! Form::label('wikititle', 'Wikipedia Page Title', array('class' => 'control-label mt-5', 'required')) !!}
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <div class="form-group">

                            {!! Form::text('wikititle', null, array('class' => 'form-control', 'id' => 'wikititle')) !!}
                            @if ($errors->has('wikititle'))
                                <span class="help-block validation-error-label" for="wikititle">{{ $errors->first('wikititle') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <button type="submit" class="btn btn-primary">Get Wiki Page<i class="far fa-eye position-right"></i></button>
                    </div>
                </div>
                {!! Form::close() !!}

            </div>
        </div>

    </div>

</div>