@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{ url('assets/js/categories.js')}}"></script>
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
    {!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Dealer Categories' => '/admin/dealers/categories', $category->name => '/admin/dealers/category/'.$category->slug, $childCategory->name => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Dealer Categories</span> - Manage the categories
@stop


@section('content')

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Child Category</div>
            <div class="panel-body">

                {!! Form::model($childCategory, array('method' => 'POST', 'url' => '/admin/dealers/category/'.$category->slug.'/child/'.$childCategory->slug.'/update', 'class' => 'form-horizontal', 'id' => 'category-update', 'autocomplete' => 'off')) !!}
                @include('base::admin.Categories.Category-Form')
                <div class="form-group">
                    <div class="col-lg-6">
                        {!! Form::button('<i class="far fa-save"></i> Edit Child Category', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-lg-6">
                        @if($childCategory->system == 0)
                        {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/category/'.$category->slug.'/child/'.$childCategory->slug.'/delete', 'class' => 'form-horizontal', 'id' => 'category-update', 'autocomplete' => 'off')) !!}
                        {!! Form::button('<i class="far fa-times"></i> Delete Child Category', array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                        {!! Form::close() !!}
                        @endif
                    </div>
                </div>


            </div>
        </div>

    </div>

    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Child Categories</div>
            <div class="panel-body">

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Created On</th>
                        <th>Updated On</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($childCategories as $childCategory)

                        <tr>
                            <td>@if($childCategory->icon)<i class="{{$childCategory->icon}}"></i>&nbsp;&nbsp;@endif{{ $childCategory->name }}</td>
                            <td>{{ $childCategory->slug }}</td>
                            <td>{{ $childCategory->created_at->format('dS M Y H:i:s') }}</td>
                            <td>{{ $childCategory->updated_at->diffForHumans() }}</td>
                            <td><a href='{{ url("admin/dealers/category/".$category->slug.'/child/'.$childCategory->slug) }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="far fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>


@stop