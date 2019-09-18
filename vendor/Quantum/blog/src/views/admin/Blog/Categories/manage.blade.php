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
    {!! breadcrumbs(['Dashboard' => '/admin/dashboard', 'Categories' => '/admin/categories', $category->name => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Site Categories</span> - Manage the categories
@stop


@section('content')

    <div class="col-md-4">
        @if($category->system != 1)
        <div class="panel panel-default">
            <div class="panel-heading">Edit Top Level Category</div>
            <div class="panel-body">

                {!! Form::model($category, array('method' => 'POST', 'url' => '/admin/category/'.$category->slug.'/update', 'class' => 'form-horizontal', 'id' => 'category-update', 'autocomplete' => 'off')) !!}
                @include('admin.Blog.Categories.Category-Form')
                <div class="form-group">
                    <div class="col-lg-6">
                        {!! Form::button('<i class="far fa-save"></i> Edit Top Level Category', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-lg-6">
                        {!! Form::open(array('method' => 'POST', 'url' => '/admin/category/'.$category->slug.'/delete', 'class' => 'form-horizontal', 'id' => 'category-update', 'autocomplete' => 'off')) !!}
                        {!! Form::button('<i class="far fa-times"></i> Delete Top Level Category', array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                        {!! Form::close() !!}
                    </div>
                </div>


            </div>
        </div>
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">Create Child Category</div>
            <div class="panel-body">

                {!! Form::open(array('method' => 'POST', 'url' => '/admin/category/'.$category->slug.'/create-child', 'class' => 'form-horizontal', 'id' => 'categoryChild-create', 'autocomplete' => 'off')) !!}
                @include('admin.Blog.Categories.Child-Form')
                <div class="form-group">
                    <div class="col-lg-10">
                        {!! Form::button('<i class="far fa-save"></i> Create Child Category', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                    </div>
                </div>
                {!! Form::hidden('parent_id', $category->id) !!}
                {!! Form::close() !!}

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
                            <td><a href='{{ url("admin/category/".$category->slug.'/child/'.$childCategory->slug) }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="far fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>


@stop