<?php
$pageRoute = Auth::check() ? '/members' : '';
?>
<div style="background: rgba(36, 41, 49, 1); padding-top:33px;border: solid 1px #242931;margin-bottom:60px;-webkit-box-shadow: 0 0 5px rgba(0,0,0,.4), inset 1px 2px rgba(255,255,255,.3);-moz-box-shadow: 0 0 5px rgba(0,0,0,.4), inset 1px 2px rgba(255,255,255,.3);box-shadow: 0 0 5px rgba(0,0,0,.4), inset 0px 2px rgba(255,255,255,.3);
@if(Auth::user())
@if(Auth::user()->hasRole(Settings::get('main_content_role')))margin-top:-30px;-webkit-box-shadow: 0 0 0px rgba(0,0,0,.4), inset 0px 0px rgba(255,255,255,.3);-moz-box-shadow: 0 0 0px rgba(0,0,0,.4), inset 0px 0px rgba(255,255,255,.3);box-shadow: 0 0 0px rgba(0,0,0,.4), inset 0px 0px rgba(255,255,255,.3);
@endif
@endif" class="page-section">
    <div class="container">
        <div class="row">
            <div class="section-fullwidtht col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <!--Element Section Start-->
                    <div class="cs-listing-filters">
                    <div class="cs-search">
                        {!! Form::open(array('method' => 'POST', 'url' => $pageRoute.'/vehicles', 'class' => 'search-form')) !!}

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="select-input select-location">
                            {!! Form::select('location', $dealerLocation, 0, ['class' => 'chosen-select', 'id' => 'location', 'autocomplete' => 'false', 'data-placeholder' => 'Select Location', 'tabindex' => '-1']) !!}
                        </div>
                            </div>

                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                        <div class="select-input select-make">
                            {!! Form::select('vehicleMake', $vehicleMakes, 0, ['class' => 'chosen-select', 'id' => 'vehicleMake', 'autocomplete' => 'false', 'data-placeholder' => 'Select Make', 'tabindex' => '-1']) !!}
                        </div>
                            </div>
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12" id="modelSearch">
                        <div class="select-input select-model">
                            {!! Form::select('vehicleModel', $vehicleModels, 0, ['class' => 'chosen-select', 'id' => 'vehicleModel', 'autocomplete' => 'false', 'data-placeholder' => 'Select Model', 'tabindex' => '-1']) !!}
                        </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12" id="textSearch">
                            <div class="loction-search vehicle-search">
                                {!! Form::text('search', '', ['class' => '', 'id' => 'search', 'autocomplete' => 'false', 'placeholder' => 'Search', 'tabindex' => '-1', 'data-toggle' =>'tooltip', 'data-placement'=>'bottom', 'data-html'=>"true", 'title'=>'No Models Found.<br>Try a keyword search instead.']) !!}

                            </div>
                        </div>

                            <div class="col-lg-1 col-md-1 col-sm-3 col-xs-12">
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