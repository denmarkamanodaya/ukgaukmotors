@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => 'admin/newsletter', 'Mail' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletters</span> - Manage Mail
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
                    <a href="{{url('/admin/newsletter/mail/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-retweet"></i>
                            </b>
                            Create Mail Shot
                        </button>
                    </a>&nbsp;&nbsp;
                    <br><br>
                    <p>Mail Shots listed below are either queued to be sent or are in progress of sending. Mail is sent out in maximum batches of {{ config('newsletter.newsletter_batch') }} every 5 minutes<br>Newsletters with a subscriber count over the batch limit will stay on list until all have been sent to.</p>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-md-2">Newsletter</th>
                            <th class="col-md-2">Title</th>
                            <th class="col-md-2">Send On</th>
                            <th class="col-md-1">Sent To</th>
                            <th class="col-md-1">Working</th>
                            <th class="col-md-1">Active</th>
                            <th class="col-md-1">Updated At</th>
                            <th class="col-md-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($timedMails as $timedMail)
                            <tr>
                                <td>{{$timedMail->newsletter->title}}</td>
                                <td>{{$timedMail->subject}}</td>
                                <td>{{$timedMail->send_on->format('d/m/Y H:i')}}</td>
                                <td>{{$timedMail->sent_count}}</td>
                                <td>@if($timedMail->in_progress == 1)<i class="far fa-check"></i>@endif</td>
                                <td>@if($timedMail->active == 1)
                                        <i class="far fa-check"></i>
                                    @else
                                        <i class="far fa-times"></i>
                                    @endif
                                </td>
                                <td>{{$timedMail->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{url('admin/newsletter/mail/'.$timedMail->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                    <a target="_blank" href="{{url('admin/newsletter/mail/'.$timedMail->id.'/preview')}}" class="btn bg-info btn-labeled ml-5" type="button"><b><i class="far fa-eye"></i></b> Preview</a>
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


