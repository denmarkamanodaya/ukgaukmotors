@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $('.confirmDelete').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this theme?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/newsletter/theme')}}/' + id + '/delete';
                    }
                }
            });
        });
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => 'admin/newsletter', 'Themes' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletters</span> - Manage Newsletter Themes
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Themes</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/newsletter/theme/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-retweet"></i>
                            </b>
                            Create Theme
                        </button>
                    </a>&nbsp;&nbsp;
                    <br><br>
                    <p>Themes control the overall look of your email. A replacement field is used as a placeholder for the main message to be inserted into.<br>A blank theme has been provided as default.</p>


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $template)
                            <tr>
                                <td>{{$template->title}}</td>
                                <td>{{$template->slug}}</td>
                                <td>{{$template->created_at->format('m/d/Y')}}</td>
                                <td>{{$template->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{url('admin/newsletter/theme/'.$template->slug.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                    <a href="{{url('admin/newsletter/theme/'.$template->slug.'/delete')}}" data-id='{{$template->slug}}' class="btn bg-danger btn-labeled confirmDelete" type="button"><b><i class="far fa-times"></i></b> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $templates->count() !!} out of {!! $templates->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $templates->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


