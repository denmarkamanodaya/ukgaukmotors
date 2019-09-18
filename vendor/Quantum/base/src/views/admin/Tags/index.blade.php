@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $('.confirmDelete').on('click', function() {
            var id = $(this).data('id');
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this tag?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/tag')}}/' + id + '/delete';
                    }
                }
            });
        });
    </script>

    <script>
        $(function() {
            $('#tags-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin_tags_data') !!}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'slug', name: 'slug'},
                    {data: 'created_at', name: 'created_at'},
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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Tags' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Site Tags</span> - Manage site tags
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Tags</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="tags-table">
                        <thead>
                        <tr>
                            <th class="col-lg-2">Tag</th>
                            <th class="col-lg-2">Slug</th>
                            <th class="col-lg-1">Created</th>
                            <th class="col-lg-1">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>

@stop


