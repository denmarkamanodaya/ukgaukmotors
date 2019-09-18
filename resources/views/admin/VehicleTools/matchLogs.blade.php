@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $(function() {
            $('#vehicletype-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin_vehicleMatchLogs_data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'vehicle_make', name: 'vehicle_make'},
                    {data: 'vehicle_model', name: 'vehicle_model'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[ 0, 'desc' ]]
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'VehiclesTools' => 'is_current', 'Unclassified' => '/admin/vehicleTools/unclassified', 'Matched Vehicle Logs' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Matched Vehicle Logs</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Matched Vehicle Logs</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehicletype-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">ID</th>
                            <th class="col-md-5">Name</th>
                            <th class="col-md-2">Assigned Make</th>
                            <th class="col-md-2">Assigned Model</th>
                            <th class="col-md-1">Created</th>
                            <th class="col-md-1">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>


    </div>
@stop


