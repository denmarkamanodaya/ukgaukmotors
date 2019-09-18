@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/acl.js')}}"></script>
    <script type="text/javascript">

        $('#roleDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this user?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Acl' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Acl</span> - Manage roles and permissions
@stop


@section('content')

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Roles</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <table id="menu-table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center">Role Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($roles as $role)
                            <tr>

                                <td class="center">{{ $role->title}}</td>
                                <td class="center">
                                    <div class="visible-md visible-lg hidden-sm hidden-xs center">
                                        <a data-original-title="Edit" data-placement="top" class="btn btn-primary tooltips pull-left" href="{{ url("admin/acl/role/$role->id/edit") }}"><i class="far fa-edit"></i> Edit</a>
                                    @if($role->system != 1)

                                            {!! Form::model($role,array('method' => 'POST', 'url' => '/admin/acl/role/'. $role->id .'/delete', 'id' => 'roleDelete')) !!}
                                            &nbsp;&nbsp;<button type="submit" class="btn btn-warning">Delete Role <i class="far fa-times position-right"></i></button>
                                            {!! Form::close() !!}
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Create a new Role</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/acl/role/create', 'class' => 'form-horizontal', 'id' => 'role-create')) !!}

                    @include('base::admin.Acl.partials.roleform')

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Create Role', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>


        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Create New Permission</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/acl/permission/create', 'class' => 'form-horizontal', 'id' => 'permission-create')) !!}

                    @include('base::admin.Acl.partials.permissionMainForm')

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Create Permission', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Permissions</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    @foreach(array_chunk($permissions->toArray(), 1) as $area)
                        <div class="col-lg-6">
                            <div class="content-group-sm">
                                <h2 class="no-margin text-semibold">{{$area[0]['title']}}</h2>
                            </div>
                            @foreach($area[0]['permissiongroups'] as $group)

                                <div class="content-group-sm">
                                    <h5 class="no-margin text-semibold">{{$group['title']}}</h5>
                                </div>

                                @foreach($group['permissions'] as $perm)
                                    <div class="content-group-sm">
                                        {{$perm['title']}}
                                        @if(!$perm['system'])
                                            &nbsp;&nbsp;<a href='{{ url("admin/acl/permission/".$perm['id']."/edit") }}' class="btn btn-primary btn-icon btn-rounded btn-xs" type="button"><i class="far fa-edit"></i> Edit</a>
                                        @endif
                                    </div>
                                @endforeach

                            @endforeach
                        </div>
                    @endforeach


                </div>
            </div>

        </div>
    </div>
@stop


