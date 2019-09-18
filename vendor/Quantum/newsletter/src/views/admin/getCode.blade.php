@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletter' => '/admin/newsletter', 'Get Code' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Get Signup Code
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Form Code</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="message" rows="20" cols="30" class="form-control">@include('newsletter::admin/formcode')
                                </textarea>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


        </div>


    </div>

@stop


