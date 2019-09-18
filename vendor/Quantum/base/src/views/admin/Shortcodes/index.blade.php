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
                message: 'Are you sure you want to delete this shortcode?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/shortcode')}}/' + id + '/delete';
                    }
                }
            });
        });
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Shortcodes' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Shortcodes</span> - Manage Shortcodes
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Shortcodes</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/shortcodes/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-retweet"></i>
                            </b>
                            Create Shortcode
                        </button>
                    </a><br><br>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shortcodes as $shortcode)
                            <tr>
                                <td>{{$shortcode->id}}</td>
                                <td>{{$shortcode->title}}</td>
                                <td>{{$shortcode->created_at->format('m/d/Y')}}</td>
                                <td>{{$shortcode->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{url('admin/shortcode/'.$shortcode->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$shortcode->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $shortcodes->count() !!} out of {!! $shortcodes->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $shortcodes->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


