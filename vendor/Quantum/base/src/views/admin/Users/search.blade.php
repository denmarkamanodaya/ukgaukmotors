@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Users' => '/admin/users', 'Search Results' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Users</span> - Search Results
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Found Users</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    @if($search)
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-lg-1">Id</th>
                            <th class="col-lg-1"></th>
                            <th class="col-lg-2">Username</th>
                            <th class="col-lg-3">Email</th>
                            <th class="col-lg-2">Role</th>
                            <th class="col-lg-2">Created At</th>
                            <th class="col-lg-1">Updated At</th>
                            <th class="col-lg-1">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if($search['type'] == 'first_name' || $search['type'] == 'last_name')

                            @foreach($search['users'] as $profile)
                                <tr>
                                    <td>{{$profile->user->id}}</td>
                                    <td class="text-center">
                                            {!! show_profile_pic($profile->user, 'img-responsive') !!}
                                    </td>
                                    <td>{{$profile->user->username}}</td>
                                    <td>{{$profile->user->email}}</td>
                                    <td>
                                        @foreach($profile->user->role as $role)
                                            <li>{{$role->title}}</li>
                                        @endforeach
                                    </td>
                                    <td>{{$profile->user->created_at->format('d/m/Y h:i:s')}}</td>
                                    <td>{{$profile->user->updated_at->diffForHumans()}}</td>
                                    <td><a href="{{url('admin/user/'.$profile->user->username.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="icon-reading"></i></b> Details</a></td>
                                </tr>
                            @endforeach

                        @endif

                        @if($search['type'] == 'email')

                            @foreach($search['users'] as $user)
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
                                    <td>{{$user->created_at->format('d/m/Y h:i:s')}}</td>
                                    <td>{{$user->updated_at->diffForHumans()}}</td>
                                    <td><a href="{{url('admin/user/'.$user->username.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="icon-reading"></i></b> Details</a></td>
                                </tr>
                            @endforeach

                        @endif

                        @if($search['type'] == 'transactionid')

                            @foreach($search['users'] as $transaction)
                                <tr>
                                    <td>{{$transaction->user->id}}</td>
                                    <td class="text-center">
                                            {!! show_profile_pic($transaction->user, 'img-responsive') !!}
                                    </td>
                                    <td>{{$transaction->user->username}}</td>
                                    <td>{{$transaction->user->email}}</td>
                                    <td>
                                        @foreach($transaction->user->role as $role)
                                            <li>{{$role->title}}</li>
                                        @endforeach
                                    </td>
                                    <td>{{$transaction->user->created_at->format('d/m/Y h:i:s')}}</td>
                                    <td>{{$transaction->user->updated_at->diffForHumans()}}</td>
                                    <td><a href="{{url('admin/user/'.$transaction->user->username.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="icon-reading"></i></b> Details</a></td>
                                </tr>
                            @endforeach

                        @endif



                        </tbody>
                    </table>

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $search['users']->count() !!}
                        </div>
                    </div>
                        @else
                    <div class="row">
                        <div class="col-md-12"><h4>No Users Found</h4></div>
                    </div>
                    @endif

                </div>
            </div>

        </div>

    </div>
@include('base::admin.Users.partials.searchmodal')
@stop


