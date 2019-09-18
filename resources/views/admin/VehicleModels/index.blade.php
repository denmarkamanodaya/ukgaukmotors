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
                @if($search)
                ajax: '{!! url('admin/vehicle-models/data/'.$search->slug) !!}',
                @else
                ajax: '{!! url('admin/vehicle-models/data/') !!}',
                @endif
                columns: [
                    {data: 'id', name: 'id', searchable: false},
                    {data: 'make.name', name: 'make.name', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description.content', searchable: false},
                    {data: 'featured_image', name: 'description.featured_image', searchable: false},
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
    @if($search)
        {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => '/admin/vehicle-makes', $search->name => 'is_current')) !!}
    @else
        {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Makes' => 'is_current')) !!}
    @endif
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Models</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Vehicle Models</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    @if($search)
                    <div class="text-right mb-20">
                        <a href="{{ url('/admin/vehicle-models/'.$search->slug.'/create')}}">
                            <button class="btn bg-teal-400 btn-labeled" type="button">
                                <b>
                                    <i class="fas fa-car"></i>
                                </b>
                                Create New Vehicle Model
                            </button>
                        </a>
                    </div>
                    @endif

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehiclemodels-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Id</th>
                            <th class="col-md-2">Make</th>
                            <th class="col-md-2">Model</th>
                            <th class="col-md-1 center">Description</th>
                            <th class="col-md-1 center">Image</th>
                            <th class="col-md-1">Created</th>
                            <th class="col-md-4">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>
@stop


