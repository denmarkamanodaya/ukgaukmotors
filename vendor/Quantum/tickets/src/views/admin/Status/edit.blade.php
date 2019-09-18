@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{url('assets/js/categories.js')}}"></script>
    <script src="{{ url("assets/js/jquery.fonticonpicker.min.js") }}"></script>
    <script>
        var fa_icons = {!! $fajson !!};
        $('.iconPicker').fontIconPicker({
            theme: 'fip-bootstrap',
            source: fa_icons,
            useAttribute: false,
            emptyIconValue: 'none'
        });
    </script>
@stop

@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/icons/fontIconPicker/css/jquery.fonticonpicker.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/icons/fontIconPicker/themes/bootstrap-theme/jquery.fonticonpicker.bootstrap.min.css')}}" />
    <link href="{{url('assets/css/icons/fontawesome5/css/fontawesome-all.css')}}" rel="stylesheet">
@stop

@section('breadcrumbs')
{!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Ticket Statuses' => 'is_current']) !!}
@stop

@section('page-header')
<span class="text-semibold">Ticket Statuses</span> - Manage the statuses
@stop


@section('content')

    <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Create A Status</div>
                <div class="panel-body">

                    {!! Form::model($status, array('method' => 'POST', 'url' => '/admin/ticket/status/'.$status->slug.'/update', 'class' => 'form-horizontal', 'id' => 'status-update', 'autocomplete' => 'off')) !!}
@include('tickets::admin.Status.statusForm')
                    <div class="form-group">
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Update Status', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                    @if($status->system == 0)
                    <div class="col-lg-12">
                        {!! Form::model($status, array('method' => 'POST', 'url' => '/admin/ticket/status/'.$status->slug.'/delete', 'class' => 'form-horizontal', 'id' => 'status-delete', 'autocomplete' => 'off')) !!}
                        <div class="form-group">
                            <div class="col-lg-12 text-right">
                                {!! Form::button('<i class="far fa-times"></i> Delete Status', array('type' => 'submit', 'class' => 'btn btn-warning')) !!}
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
                <div class="panel-heading">All Statuses</div>
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
                        @foreach($statuses as $status)

                            <tr>
                                <td>{!! statusIcon($status) !!} {{ $status->name }}</td>
                                <td>{{ $status->description }}</td>
                                <td>{!! isDefaultStatus($status) !!}</td>
                                <td>{{ $status->created_at->format('dS M Y H:i:s') }}</td>
                                <td>{{ $status->updated_at->diffForHumans() }}</td>
                                <td><a href='{{ url("admin/ticket/status/".$status->slug.'/edit') }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="far fa-edit"></i> Manage</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
    </div>


@stop