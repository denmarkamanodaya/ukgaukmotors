@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{ url('assets/js/jquery_ui/full.min.js')}}"></script>
    <script>
        $('.confirmDelete').on('click', function() {
            var id = $(this).data('id');
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this category?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{ url('admin/vehicle-features')}}/' + id + '/delete';
                    }
                }
            });
        });

        $("#vehicle-features-table").sortable({
            items: "tbody > tr",
            cursor: 'move',
            opacity: 0.6,
            placeholder: "ui-state-highlight",
            update: function() {
            }
        });

    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Features' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Features</span> - Manage vehicle features
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Feature Categories</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{ url('/admin/vehicle-features/create')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b>
                                <i class="far fa-book"></i>
                            </b>
                            Create New Feature Category
                        </button>
                    </a>
                    {!! Form::open(array('method' => 'POST', 'url' => 'admin/vehicle-features/savePosition')) !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehicle-features-table">
                        <thead>
                        <tr>
                            <th class="col-md-7">Name</th>
                            <th class="col-md-2">Updated At</th>
                            <th class="col-md-3">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($features as $feature)
                            <tr>{!! Form::hidden('position[]', $feature->id) !!}
                                <td>{!! hasIcon($feature) !!} {{$feature->name}}</td>
                                <td>{{$feature->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{ url('admin/vehicle-features/'.$feature->slug.'/items')}}" class="btn btn-info btn-labeled" type="button"><b><i class="far fa-book"></i></b> Manage Items</a>
                                    <a href="{{ url('admin/vehicle-features/'.$feature->slug.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit Details</a>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$feature->slug}}'><b><i class="far fa-times"></i></b> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-20">
                        <button type="submit" class="btn btn-primary">Save Positions <i class="far fa-save position-right"></i></button>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>

        </div>

    </div>

@stop


