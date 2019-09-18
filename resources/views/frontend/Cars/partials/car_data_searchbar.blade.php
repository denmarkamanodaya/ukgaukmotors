<?php
$pageRoute = Auth::check() ? '/members' : '';
?>
<div style="background: rgba(36, 41, 49, 1); padding-top:33px;border: solid 1px #242931;margin-bottom:0px;-webkit-box-shadow: 0 0 5px rgba(0,0,0,.4), inset 1px 2px rgba(255,255,255,.3);-moz-box-shadow: 0 0 5px rgba(0,0,0,.4), inset 1px 2px rgba(255,255,255,.3);box-shadow: 0 0 5px rgba(0,0,0,.4), inset 0px 2px rgba(255,255,255,.3);
@if(Auth::user())
@if(Auth::user()->hasRole(Settings::get('main_content_role')))margin-top:-30px;-webkit-box-shadow: 0 0 0px rgba(0,0,0,.4), inset 0px 0px rgba(255,255,255,.3);-moz-box-shadow: 0 0 0px rgba(0,0,0,.4), inset 0px 0px rgba(255,255,255,.3);box-shadow: 0 0 0px rgba(0,0,0,.4), inset 0px 0px rgba(255,255,255,.3);
@endif
@endif" class="page-section">
    <div class="container">
        <div class="row">
            <div class="section-fullwidtht col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="carDBSearchTitle col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        The Big Car Database
                    </div>

                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                        <!--Element Section Start-->
                        <div class="cs-listing-filters carDBSearchFields">
                            <div class="cs-search">
                                {!! Form::open(array('method' => 'POST', 'url' => $pageRoute.'/motorpedia/search', 'class' => 'search-form', 'id' => 'carDataSearch', 'autocomplete' => 'false')) !!}
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group select-input select-make">
                                        {!! Form::select('vehicleMakeData', $carMakesList, 0, ['class' => 'chosen-select', 'id' => 'vehicleMakeData', 'autocomplete' => 'false', 'data-placeholder' => 'Select Make', 'tabindex' => '-1']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group select-input select-model">
                                        {!! Form::select('vehicleModel', $vehicleModels, 0, ['class' => 'chosen-select', 'id' => 'vehicleModel', 'autocomplete' => 'false', 'data-placeholder' => 'Select Model', 'tabindex' => '-1']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="cs-field-holder text-center">
                                        <div class="cs-btn-submit">
                                            <input type="submit" value="Search Vehicles">
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <!--Element Section End-->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>