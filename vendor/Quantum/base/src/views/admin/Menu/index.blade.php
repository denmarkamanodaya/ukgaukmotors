@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Menu' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Menu</span> - Manage site Menu's
@stop


@section('content')
    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Current Menu's</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <table id="menu-table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center">Menu Name</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($menus as $menu)
                            <tr>

                                <td class="center">{{ $menu->title}}</td>
                                <td>{{ $menu->description}}</td>
                                <td class="center">
                                    <div class="visible-md visible-lg hidden-sm hidden-xs center">
                                        <a data-original-title="Edit" data-placement="top" class="btn btn-primary tooltips" href="{{ url("admin/menu/$menu->id/edit") }}"><i class="far fa-edit"></i> Edit</a>
                                        @if(!$menu->system)
                                            &nbsp;&nbsp;<a data-original-title="Delete" data-placement="top" class="btn btn-danger tooltips" href="{{ url("admin/menu/$menu->id/delete") }}"><i class="far fa-times"></i> Delete</a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Create a new Menu</h6>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                            <li><a data-action="reload"></a></li>
                            <li><a data-action="close"></a></li>
                        </ul>
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/menu/create/', 'class' => 'form-horizontal')) !!}

                    @include('base::admin.Menu.partials.form')

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Create Menu', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>
    </div>
@stop


