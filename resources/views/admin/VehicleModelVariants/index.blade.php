@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $(function() {
            $('#vehiclemodels-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('admin/vehicle-model-variants/data/'.$vehicleModel->id) !!}',
                columns: [
                    {data: 'model_name', name: 'model_name', searchable: false},
                    {data: 'model_desc', name: 'model_desc'},
                    {data: 'default', name: 'default', searchable: false},
                    {data: 'description', name: 'description.content', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[ 1, 'asc' ]]
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => '/admin/vehicle-makes', $vehicleMake->name => '/admin/vehicle-models/'.$vehicleMake->slug, $vehicleModel->name => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Variants</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicle Variants : {{ $vehicleMake->name }} {{ $vehicleModel->name }}</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehiclemodels-table">
                        <thead>
                        <tr>
                            <th class="col-md-2">Variant</th>
                            <th class="col-md-3">Description</th>
                            <th class="col-md-1">Default</th>
                            <th class="col-md-1">Desc Page</th>
                            <th class="col-md-2">Created</th>
                            <th class="col-md-3">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>
@stop


