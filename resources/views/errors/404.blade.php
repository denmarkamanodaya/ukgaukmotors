@extends('base::frontend.Template')


@section('page_title')
    @stop

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
@stop

@section('page-header')
    <span class="text-semibold">Pages</span> - Manage site pages
    @stop


    @section('content')
            <!-- Error wrapper -->
            <div style=" padding-top: 280px; padding-bottom:280px;" class="page-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="cs-page-not-found">
                                <div class="cs-text">
                                    <p>Sorry, but the page that you requested doesn't exist.</p>
                                    <span class="cs-error">404<em>Error</em></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="cs-seprater-v1">
                                <span><i class="icon-home2 cs-bgcolor"> </i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <!-- /error wrapper -->

@stop


