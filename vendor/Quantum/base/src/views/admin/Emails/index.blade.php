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
                message: 'Are you sure you want to delete this email?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/email')}}/' + id + '/delete';
                    }
                }
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Emails' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Emails</span> - Manage system emails
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">System Emails</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/emails/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-file-plus"></i>
                            </b>
                            Create a new Email
                        </button>
                    </a><br/><br/>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($emails as $email)
                            <tr>
                                <td>{{$email->id}}</td>
                                <td>{{$email->title}}</td>
                                <td>
                                    <a href="{{url('admin/email/'.$email->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                    @if(!$email->system)
                                        &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$email->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $emails->count() !!} out of {!! $emails->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $emails->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


