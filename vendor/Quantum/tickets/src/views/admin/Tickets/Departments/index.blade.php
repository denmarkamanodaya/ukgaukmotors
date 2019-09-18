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
<span class="text-semibold">Ticket Departments</span> - Manage the departments
@stop


@section('content')

    <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Create A Department</div>
                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/ticket/department/create', 'class' => 'form-horizontal', 'id' => 'department-create', 'autocomplete' => 'off')) !!}
@include('admin.Tickets.Departments.departmentForm')
                    <div class="form-group">
                        <div class="col-lg-10">
                            {!! Form::button('<i class="fa fa-save"></i> Create New Department', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

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
                                <td><a href='{{ url("admin/ticket/department/".$department->slug.'/edit') }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="fa fa-edit"></i> Manage</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
    </div>


@stop