@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => '/admin/newsletter', 'Subscribers' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Subscribers</span> - Manage newsletter subscribers
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Site Users</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/newsletter/subscriber/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b><i class="far fa-user"></i></b>Create New Subscriber
                        </button>
                    </a>
                    <button type="button" class="btn btn-primary btn-labeled ml-10" data-toggle="modal" data-target="#exampleModal">
                        <b><i class="far fa-search"></i></b>Search Subscribers
                    </button><br><br>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-lg-2">Email</th>
                            <th class="col-lg-1">Name</th>
                            <th class="col-lg-1">User</th>
                            <th class="col-lg-2">Newsletter</th>
                            <th class="col-lg-2">Responder Sequence</th>
                            <th class="col-lg-2">Created At</th>
                            <th class="col-lg-1">Bounced</th>
                            <th class="col-lg-1">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subscribers as $subscriber)
                            <tr>
                                <td>{{$subscriber->email}}</td>
                                <td>{{$subscriber->first_name}} {{$subscriber->last_name}}</td>
                                <td>{!! is_user($subscriber) !!}</td>
                                <td>{{$subscriber->newsletter->title}}</td>
                                <td>
                                @if($subscriber->sequence > 0)
                                        {{$subscriber->sequence}} on {!! $subscriber->sequence_send_on->format('d/m/Y h:i:s') !!}
                                    @else
                                    n/a
                                    @endif
                                </td>
                                <td>{{$subscriber->created_at->format('d/m/Y h:i:s')}}</td>
                                <td>{!! $subscriber->bounced !!}</td>
                                <td><a href="{{url('admin/newsletter/subscriber/'.$subscriber->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="icon-reading"></i></b> Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $subscribers->count() !!} out of {!! $subscribers->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $subscribers->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
    @include('newsletter::admin.subscribers.partials.searchmodal')
@stop


