@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')

@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Calendar' => '/admin/calendar', 'Importer' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Calendar Importer</span>
@stop


@section('content')

    <div class="row">

        <div class="col-lg-8 col-md-offset-2">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <div class="heading-elements">

                    </div>
                </div>

                <div class="panel-body">

                    @if($imported)
                        <p>Imported : {{$imported}}</p>
                    @else
                        <p>There was a problem with the importer</p>
                    @endif

                </div>
            </div>

        </div>

    </div>



@stop


