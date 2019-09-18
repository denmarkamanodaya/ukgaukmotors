@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $(function() {
            $('#auctioneers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin_auctioneers_data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'logo', name: 'logo', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'calendar_events_count', name: 'calendar_events_count'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[ 2, 'asc' ]]
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneers</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title"></h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="auctioneers-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Id</th>
                            <th class="col-md-1 text-center"></th>
                            <th class="col-md-6">Name</th>
                            <th class="col-md-1 text-center">Events</th>
                            <th class="col-md-2">Created</th>
                            <th class="col-md-1">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>
@stop


