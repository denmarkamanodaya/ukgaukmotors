@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/acl.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Acl' => 'admin/acl', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Role</span> - Edit the role and its permissions
@stop


@section('content')
    {!! Form::model($role,array('method' => 'POST', 'url' => '/admin/acl/role/'.$role->id.'/update', 'class' => 'form-horizontal', 'id' => 'role-edit')) !!}

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit The Role</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    @include('base::admin.Acl.partials.roleform')

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

                    @include('base::admin.Acl.partials.permissionForm')

                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Commit Settings</h6>
                </div>

                <div class="panel-body">
                    {!! Form::button('<i class="far fa-save"></i> Save Role Settings', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                </div>
            </div>

        </div>
    </div>

    {!! Form::close() !!}
@stop


