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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Calendar' => '/admin/calendar', 'Import' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Calendar Import</span>
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

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-lg-7">Title</th>
                            <th class="col-lg-4">Select Dealer</th>
                            <th class="col-lg-1">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            {!! Form::open(array('method' => 'POST', 'url' => '/admin/calendar/import', 'autocomplete' => 'off')) !!}
                            {!! Form::hidden('post', $post->id) !!}

                            <tr>
                                <td>{{$post->title}}</td>
                                <td>{!! Form::select('dealer', $dealers, null, ['class' => 'form-control', 'autocomplete' => 'false', 'data-placeholder' => 'Select Dealer', 'tabindex' => '-1']) !!}</td>
                                <td><button type="submit" class="btn btn-primary">Import<i class="fas fa-italic position-right"></i></button></td>
                            </tr>
                            {!! Form::close() !!}
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $posts->count() !!} out of {!! $posts->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $posts->render() !!}
                        </div>
                    </div>


                </div>
            </div>

        </div>

    </div>



@stop


