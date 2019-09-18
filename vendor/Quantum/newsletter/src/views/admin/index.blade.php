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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Newsletters' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Newsletters</span> - Manage Newsletter Campaigns
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Newsletters</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/newsletter/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-retweet"></i>
                            </b>
                            Create Newsletter
                        </button>
                    </a>&nbsp;&nbsp;
                    <a href="{{url('/admin/newsletter/subscriber/create')}}">
                        <button class="btn bg-success btn-labeled" type="button">
                            <b>
                                <i class="far fa-user"></i>
                            </b>
                            Create Subscriber
                        </button>
                    </a><br><br>

                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Responders</th>
                            <th>Subscribers</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($newsletters as $newsletter)
                            <tr>
                                <td>{{$newsletter->id}}</td>
                                <td>{{$newsletter->title}}</td>
                                <td>{{$newsletter->responders_count}}</td>
                                <td>{{$newsletter->subscribers_active_count}}</td>
                                <td>{{$newsletter->created_at->format('m/d/Y')}}</td>
                                <td>{{$newsletter->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{url('admin/newsletter/'.$newsletter->slug.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                    <a href="{{url('admin/newsletter/'.$newsletter->slug.'/responders')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-clock"></i></b> Responders</a>
                                    <a href="{{url('admin/newsletter/'.$newsletter->slug.'/delete')}}" class="btn bg-danger btn-labeled" type="button"><b><i class="far fa-times"></i></b> Delete</a>
                                    <a href="{{url('admin/newsletter/'.$newsletter->slug.'/getCode')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-code"></i></b> Get Code</a>
                                    <a href="{{url('admin/newsletter/subscriber/create/'.$newsletter->slug)}}" class="btn bg-success btn-labeled" type="button"><b><i class="far fa-user"></i></b> Add</a>
                                    <a href="{{url('admin/newsletter/subscribers/'.$newsletter->slug)}}" class="btn bg-success btn-labeled" type="button"><b><i class="far fa-users"></i></b> Subscribers</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $newsletters->count() !!} out of {!! $newsletters->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $newsletters->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


