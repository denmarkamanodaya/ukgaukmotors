@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => 'admin/newsletter', 'Mail Log' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletters</span> - Mail Log
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Mail Shots</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Newsletter</th>
                            <th>Subject</th>
                            <th>Amount Sent</th>
                            <th>Amount Opened</th>
                            <th>Sent On</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($timedMails as $timedMail)
                            <tr>
                                <td>{{$timedMail->newsletter->title}}</td>
                                <td>{{$timedMail->subject}}</td>
                                <td>{{$timedMail->sent_count}}</td>
                                <td>{{$timedMail->opened_count}}</td>
                                <td>{{$timedMail->sent_on->format('d/m/Y H:i')}}</td>
                                <td>
                                    <a href="{{url('admin/newsletter/maillog/'.$timedMail->id)}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-envelope"></i></b> Details</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $timedMails->count() !!} out of {!! $timedMails->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $timedMails->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


