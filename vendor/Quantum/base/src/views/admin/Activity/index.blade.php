@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $(function() {
            $('#activity-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin_activity_data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user.username', name: 'user.username'},
                    {data: 'text', name: 'text'},
                    {data: 'created_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }}
                ],
                order: [[ 0, 'desc' ]]
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Activity' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">User Activity</span> - Past 60 days
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Activity</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="activity-table">
                        <thead>
                        <tr>
                            <th class="col-lg-1">Id</th>
                            <th class="col-lg-2">Username</th>
                            <th class="col-lg-3">Action</th>
                            <th class="col-lg-1">When</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>
@stop


