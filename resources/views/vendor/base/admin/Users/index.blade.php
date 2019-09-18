@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Users' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Users</span> - Manage site users
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
                    <a href="{{ url('/admin/user/create')}}">
                        <button class="btn bg-teal-400 btn-labeled" type="button">
                            <b><i class="far fa-user"></i></b>Create New User
                        </button>
                    </a>
                    <button type="button" class="btn btn-primary btn-labeled ml-10" data-toggle="modal" data-target="#exampleModal">
                        <b><i class="far fa-search"></i></b>Search Users
                    </button>
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/users/', 'class' => 'form-horizontal')) !!}

                    <h3>Displaying Role : {{$searchrole->title}}</h3>
                    <div class="form-group">
                        {!! Form::label('user_role', 'Select User Role:', ['class' => 'control-label col-lg-1']) !!}
                        <div class="col-lg-1">
                            {!! Form::select('user_role', $roles, null, ['class' => 'form-control']) !!}
                            {!!inputError($errors, 'user_role')!!}
                        </div>
                        <div class="col-lg-1">
                            {!! Form::button('<i class="far fa-pencil"></i> Select', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}

                        </div>
                    </div>
                    {!! Form::close() !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-lg-1">Id</th>
                            <th class="col-lg-1"></th>
                            <th class="col-lg-2">Username</th>
                            <th class="col-lg-2">Email</th>
                            <th class="col-lg-2">Role</th>
                            <th class="col-lg-1">Reg At</th>
                            <th class="col-lg-1">Created At</th>
                            <th class="col-lg-1">Updated At</th>
                            <th class="col-lg-1">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td class="text-center">
                                        {!! show_profile_pic($user, 'img-responsive') !!}

                                </td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @foreach($user->role as $role)
                                       <li>{{$role->title}}</li>
                                    @endforeach
                                </td>
                                <td>{{$user->registered_at}}</td>
                                <td>{{$user->created_at->format('d/m/Y h:i:s')}}</td>
                                <td>{{$user->updated_at->diffForHumans()}}</td>
                                <td><a href="{{ url('admin/user/'.$user->username.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="icon-reading"></i></b> Details</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $users->count() !!} out of {!! $users->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $users->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
    @include('base::admin.Users.partials.searchmodal')
@stop


