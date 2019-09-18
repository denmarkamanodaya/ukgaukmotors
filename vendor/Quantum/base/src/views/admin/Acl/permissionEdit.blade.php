@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Acl' => 'admin/acl', 'Permission Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Permission</span> - Edit the permission
@stop


@section('content')

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit The Permission</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($permission,array('method' => 'POST', 'url' => '/admin/acl/permission/'.$permission->id.'/update', 'class' => 'form-horizontal')) !!}
                    @include('base::admin.Acl.partials.permissionMainForm')

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-pencil"></i> Edit Permission', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Remove Permission</h6>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                            <li><a data-action="reload"></a></li>
                            <li><a data-action="close"></a></li>
                        </ul>
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    {!! Form::model($permission,array('method' => 'POST', 'url' => '/admin/acl/permission/'.$permission->id.'/delete', 'class' => 'form-horizontal')) !!}
                    {!! Form::button('<i class="far fa-times"></i> Delete Permission', array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

@stop


