@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{ url('assets/js/features.js')}}"></script>
    <script>
        var fa_icons = {!! $fajson !!};
    </script>
    <script src="{{ url("assets/js/jquery.fonticonpicker.min.js") }}"></script>
    <script>
        $('.iconPicker').fontIconPicker({
            theme: 'fip-bootstrap',
            source: fa_icons,
            useAttribute: false,
            emptyIconValue: 'none'
        });
    </script>
@stop

@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/icons/fontIconPicker/css/jquery.fonticonpicker.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/icons/fontIconPicker/themes/bootstrap-theme/jquery.fonticonpicker.bootstrap.min.css')}}" />
    <link href="{{ url('assets/css/icons/fontawesome5/css/fontawesome-all.css')}}" rel="stylesheet">
@stop

@section('breadcrumbs')
{!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Dealer Features' => 'is_current']) !!}
@stop

@section('page-header')
<span class="text-semibold">Dealer Features</span> - Manage the features
@stop


@section('content')

    <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Create A Feature</div>
                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/features', 'class' => 'form-horizontal', 'id' => 'feature-create', 'autocomplete' => 'off')) !!}
                    @include('admin.Auctioneers.Features.Features-Form')
                    <div class="form-group">
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Create New Feature', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
    </div>

    <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Features</div>
                <div class="panel-body">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Usage Count</th>
                            <th>Updated On</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($features as $feature)

                            <tr>
                                <td>@if($feature->icon)<i class="{{$feature->icon}}"></i>&nbsp;&nbsp;@endif{{ $feature->name }}</td>
                                <td>{{ $feature->slug }}</td>
                                <td>{{ $feature->dealer_count }}</td>
                                <td>{{ $feature->updated_at->diffForHumans() }}</td>
                                <td><a href='{{ url("admin/dealers/features/".$feature->slug) }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="far fa-edit"></i> Manage</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
    </div>


@stop