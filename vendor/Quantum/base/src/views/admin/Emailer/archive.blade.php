@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Emailer' => '/admin/emailer', 'Archive' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Email Archive</span> - Sent Emails
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Emails</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>
                <div class="panel-body">
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Sent At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($emails as $email)
                            <tr>
                                <td>
                                    @if($email->user)
                                        {{$email->user->username}}
                                    @endif
                                </td>
                                <td>{{$email->subject}}</td>
                                <td>{{$email->created_at->format('d F Y - H:i')}}</td>
                                <td>
                                    <a href="{{url('admin/emailer/archive/'.$email->id)}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-eye"></i></b> View Email</a>
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


