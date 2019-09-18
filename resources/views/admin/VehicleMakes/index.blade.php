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
                    {data: 'id', name: 'id', searchable: false},
                    {data: 'logo', name: 'logo', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description.content', searchable: false},
                    {data: 'featured_image', name: 'description.featured_image', searchable: false},
                    {data: 'country', name: 'country.name', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Makes</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicle Makes</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <div class="text-right mb-20">
                        <a href="{{ url('/admin/vehicle-makes/create')}}">
                            <button class="btn bg-teal-400 btn-labeled" type="button">
                                <b>
                                    <i class="fas fa-maxcdn"></i>
                                </b>
                                Create New Vehicle Make
                            </button>
                        </a>
                    </div>



                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehiclemakes-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Id</th>
                            <th class="col-md-1">Logo</th>
                            <th class="col-md-3">Name</th>
                            <th class="col-md-1 center">Description</th>
                            <th class="col-md-1 center">Image</th>
                            <th class="col-md-1 center">Country</th>
                            <th class="col-md-1">Created</th>
                            <th class="col-md-3">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>
@stop


