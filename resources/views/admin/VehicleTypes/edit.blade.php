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
                ajax: '{!! route('admin_vehicletype_data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[ 1, 'asc' ]]
            });
        });

        $('#vehicle-type-delete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this vehicle type?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Types' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Types</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicle Types</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehicletype-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Id</th>
                            <th class="col-md-7">Name</th>
                            <th class="col-md-2">Created</th>
                            <th class="col-md-1">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit A Vehicle Type</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($vehicletype, array('method' => 'POST', 'url' => '/admin/vehicle-type/'.$vehicletype->slug.'/update', 'class' => 'form-horizontal', 'id' => 'vehicle-type-edit')) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Vehicle Type:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                            {!!inputError($errors, 'name')!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-5">
                            {!! Form::button('<i class="far fa-save"></i> Update Vehicle Type', array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                            {!! Form::close() !!}
                        </div>
                        <div class="col-lg-5">
                            {!! Form::open(array('method' => 'POST', 'url' => '/admin/vehicle-type/'.$vehicletype->slug.'/delete', 'class' => 'form-horizontal', 'id' => 'vehicle-type-delete')) !!}
                            {!! Form::button('<i class="far fa-times"></i> Delete Vehicle Type', array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>

        </div>

    </div>
@stop


