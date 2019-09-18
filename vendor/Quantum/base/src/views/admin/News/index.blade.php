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
                message: 'Are you sure you want to delete this news item?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/newsItem')}}/' + id + '/delete';
                    }
                }
            });
        });
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'News' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">News</span> - Manage Members News
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">News</h6>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                            <li><a data-action="reload"></a></li>
                            <li><a data-action="close"></a></li>
                        </ul>
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/newsItem/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b>
                                <i class="far fa-file-plus"></i>
                            </b>
                            Create News
                        </button>
                    </a>

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/news/', 'class' => 'form-horizontal')) !!}

                    <h3>Displaying Area : {{ucfirst($area)}}</h3>
                    <div class="form-group">
                        {!! Form::label('area', 'Select Area:', ['class' => 'control-label col-lg-1']) !!}
                        <div class="col-lg-1">
                            {!! Form::select('area', array('public' => 'Public', 'members' => 'Members', 'admin' => 'Admin'), null, ['class' => 'form-control']) !!}
                            {!!inputError($errors, 'area')!!}
                        </div>
                        <div class="col-lg-1">
                            {!! Form::button('<i class="far fa-check"></i> Select', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}

                        </div>
                    </div>
                    {!! Form::close() !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Area</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($news as $newsItem)
                            <tr>
                                <td>{{$newsItem->id}}</td>
                                <td>{{$newsItem->title}}</td>
                                <td>{{ucfirst($newsItem->area)}}</td>
                                <td>
                                    <ul>
                                    @foreach($newsItem->roles as $role)
                                    <li>{!! $role->title !!}</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td>{{ucfirst($newsItem->status)}}</td>
                                <td>{{$newsItem->created_at->format('m/d/Y')}}</td>
                                <td>{{$newsItem->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{url('admin/newsItem/'.$newsItem->id.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit</a>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$newsItem->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $news->count() !!} out of {!! $news->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $news->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


