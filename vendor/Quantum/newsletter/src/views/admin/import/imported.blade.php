@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', 'Import Subscribers' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Import Subscribers</span> - Import newsletter subscribers
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <h3 class="text-center">Success</h3>

                    <p>You have imported {{$imported}} subscribers.</p>

                    @if($imported == 0)
                    <p>As the import was 0 this may be due to a malformed csv file or all the imports are existing subscribers. </p>
                    @endif
                </div>
            </div>

        </div>

    </div>
@stop


