@extends('admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/categories.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
{!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Ticket Departments' => 'is_current']) !!}
@stop

@section('page-header')
<span class="text-semibold">Ticket Departments</span> - Manage the Departments
@stop


@section('content')

    <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Create A Department</div>
                <div class="panel-body">

                    {!! Form::model($department, array('method' => 'POST', 'url' => '/admin/ticket/department/'.$department->slug.'/update', 'class' => 'form-horizontal', 'id' => 'department-update', 'autocomplete' => 'off')) !!}
@include('admin.Tickets.Departments.departmentForm')
                    <div class="form-group">
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Update Department', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                    @if($department->system == 0)
                    <div class="col-lg-12">
                        {!! Form::model($department, array('method' => 'POST', 'url' => '/admin/ticket/department/'.$department->slug.'/delete', 'class' => 'form-horizontal', 'id' => 'department-delete', 'autocomplete' => 'off')) !!}
                        <div class="form-group">
                            <div class="col-lg-12 text-right">
                                {!! Form::button('<i class="far fa-times"></i> Delete Department', array('type' => 'submit', 'class' => 'btn btn-warning')) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    @endif

                </div>
            </div>
    </div>

    <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">All Departments</div>
                <div class="panel-body">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Default</th>
                            <th>Created On</th>
                            <th>Updated On</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($departments as $department)

                            <tr>
                                <td>{!! statusIcon($department) !!} {{ $department->name }}</td>
                                <td>{{ $department->description }}</td>
                                <td>{!! isDefaultStatus($department) !!}</td>
                                <td>{{ $department->created_at->format('dS M Y H:i:s') }}</td>
                                <td>{{ $department->updated_at->diffForHumans() }}</td>
                                <td><a href='{{ url("admin/ticket/department/".$department->slug.'/edit') }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="far fa-edit"></i> Manage</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
    </div>


@stop