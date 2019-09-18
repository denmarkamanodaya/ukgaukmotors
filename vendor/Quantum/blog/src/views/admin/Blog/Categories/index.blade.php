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
{!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Categories' => 'is_current']) !!}
@stop

@section('page-header')
<span class="text-semibold">Site Categories</span> - Manage the categories
@stop


@section('content')

    <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Create A Top Level Category</div>
                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/categories/create', 'class' => 'form-horizontal', 'id' => 'category-create', 'autocomplete' => 'off')) !!}
                    @include('admin.Blog.Categories.Category-Form')
                    <div class="form-group">
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Create New Category', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
    </div>

    <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Top Level Categories</div>
                <div class="panel-body">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Sub Categories</th>
                            <th>Created On</th>
                            <th>Updated On</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)

                            <tr>
                                <td>@if($category->icon)<i class="{{$category->icon}}"></i>&nbsp;&nbsp;@endif{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>@if($category->childrenCount){{ $category->childrenCount->aggregate }}@else 0 @endif</td>
                                <td>{{ $category->created_at->format('dS M Y H:i:s') }}</td>
                                <td>{{ $category->updated_at->diffForHumans() }}</td>
                                <td><a href='{{ url("admin/category/".$category->slug) }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="far fa-edit"></i> Manage</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
    </div>


@stop