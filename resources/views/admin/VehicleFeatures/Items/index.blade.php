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
                message: 'Are you sure you want to delete this item?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{ url('admin/vehicle-features')}}/{{$feature->slug}}/item/' + id + '/delete';
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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Vehicle Features' => '/admin/vehicle-features', $feature->name => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Vehicle Features Items</span> - Manage vehicle features items
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Category Items</h6>
                    <div class="heading-elements">
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{ url('/admin/vehicle-features/'.$feature->slug.'/item/create')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b>
                                <i class="far fa-book"></i>
                            </b>
                            Create New Category Item
                        </button>
                    </a>
                    {!! Form::open(array('method' => 'POST', 'url' => 'admin/vehicle-features/'.$feature->slug.'/items/savePosition')) !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="vehicle-features-table">
                        <thead>
                        <tr>
                            <th class="col-md-7">Name</th>
                            <th class="col-md-2">Updated At</th>
                            <th class="col-md-3">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>{!! Form::hidden('position[]', $item->id) !!}
                                <td>{!! hasIcon($item) !!} {{$item->name}}</td>
                                <td>{{$item->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{ url('admin/vehicle-features/'.$feature->slug.'/item/'.$item->slug.'/edit')}}" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit Details</a>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$item->slug}}'><b><i class="far fa-times"></i></b> Delete</a>
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


