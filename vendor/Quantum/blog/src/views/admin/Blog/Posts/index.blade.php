@extends('admin.Template')


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
                ajax: '{!! route('admin_posts_data') !!}',
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'},
                    {data: 'area', name: 'area'},
                    {data: 'sticky', name: 'sticky'},
                    {data: 'featured', name: 'featured'},
                    {data: 'publishOnTime', name: 'publishOnTime'},
                    {data: 'publish_on', name: 'publish_on'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[ 7, 'desc' ]],
                "columnDefs": [
                    { className: "text-center", "targets": [ 3,4,5 ] }
                ]
            });
        });
    </script>
@stop

@section('page_css')
    <style>
        .posts_timed_icon{font-size: 24px; color: #0a68b4;}
        .postList_published{font-weight: bold;}
    </style>
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Posts' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Posts</span> - Manage posts
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Posts</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <div class="mb-20">
                    <a href="{{url('/admin/post/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-list-alt"></i>
                            </b>
                            Create New Post
                        </button>
                    </a></div>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="tags-table">
                        <thead>
                        <tr>
                            <th class="col-lg-2">Title</th>
                            <th class="col-lg-1">Status</th>
                            <th class="col-lg-1">Area</th>
                            <th class="col-lg-1">Sticky</th>
                            <th class="col-lg-1">Featured</th>
                            <th class="col-lg-1">Timed</th>
                            <th class="col-lg-2">Publish On</th>
                            <th class="col-lg-1">Updated At</th>
                            <th class="col-lg-1">Action</th>
                        </tr>
                        </thead>


                    </table>



                </div>
            </div>

        </div>

    </div>

@stop


