@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $(function() {
            $('#vehiclemakes-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin_vehiclemakes_data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'logo', name: 'logo'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[ 2, 'asc' ]]
            });
        });

        $('#vehicle-make-delete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this vehicle make?',
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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Makes</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicle Makes</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehiclemakes-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Id</th>
                            <th class="col-md-1">Logo</th>
                            <th class="col-md-5">Name</th>
                            <th class="col-md-2">Created</th>
                            <th class="col-md-3">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit A Vehicle Make</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    @if($vehicleMake->logo != '')
                        <div class="thumb thumb-slide">
                                {!! show_make_logo($vehicleMake) !!}

                        </div>

                        <div class="text-center mt-10 mb-10">
                            {!! Form::model($vehicleMake,array('method' => 'POST', 'url' => '/admin/vehicle-make/'.$vehicleMake->slug.'/remove-logo', 'id' => 'logoDelete')) !!}
                            <button type="submit" class="btn btn-warning">Remove Logo <i class="far fa-times position-right"></i></button>
                            {!! Form::close() !!}
                        </div>
                    @endif

                    {!! Form::model($vehicleMake, array('method' => 'POST', 'url' => '/admin/vehicle-make/'.$vehicleMake->slug.'/update', 'class' => 'form-horizontal', 'id' => 'vehicle-make-edit', 'files' => true)) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Vehicle Make:', ['class' => 'control-label col-lg-3']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                            {!!inputError($errors, 'name')!!}
                        </div>
                        @if($vehicleMake->system == '1')
                            <span class="help-block">This is a system imported make, name changes will not be actioned as will be overwritten on import updates.</span>
                        @endif
                    </div>

                        <div class="form-group">
                            {!! Form::label('logo', 'Logo:', ['class' => 'control-label col-lg-3']) !!}
                            <div class="col-md-9">
                                {!! Form::file('logo') !!}
                                {!!inputError($errors, 'logo')!!}
                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('country_id', 'Country:', ['class' => 'control-label col-lg-3']) !!}
                            <div class="col-lg-9">
                                {!! Form::select('country_id', $countrylist, null, ['class' => 'form-control', 'id' => 'country_id', 'autocomplete' => 'false']) !!}
                                {!!inputError($errors, 'country')!!}
                            </div>
                        </div>



                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-5">
                            {!! Form::button('<i class="far fa-save"></i> Update Vehicle Make', array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                            {!! Form::close() !!}
                        </div>
                        @if($vehicleMake->system == '0')
                        <div class="col-lg-5">
                            {!! Form::open(array('method' => 'POST', 'url' => '/admin/vehicle-make/'.$vehicleMake->slug.'/delete', 'class' => 'form-horizontal', 'id' => 'vehicle-make-delete')) !!}
                            {!! Form::button('<i class="far fa-times"></i> Delete Vehicle Make', array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                            {!! Form::close() !!}
                        </div>
                            @endif
                    </div>

                </div>
            </div>

        </div>

    </div>
@stop


