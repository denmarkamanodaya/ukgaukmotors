@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        var getItemUrl = '{{url("/admin/menu/$menu->id/get-item")}}';
        var token = '{{ csrf_token() }}';
        var menu = '{{$menu->id}}';
        var fa_icons = {!! $fajson !!};
    </script>
    <script src="{{ url("assets/js/jquery.nestable.js") }}"></script>
    <script src="{{ url("assets/js/menu.js") }}"></script>
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
    <link href="{{url('assets/css/jquery.nestable.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/icons/fontIconPicker/css/jquery.fonticonpicker.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/icons/fontIconPicker/themes/bootstrap-theme/jquery.fonticonpicker.bootstrap.min.css')}}" />
    <link href="{{url('assets/css/icons/fontawesome5/css/fontawesome-all.css')}}" rel="stylesheet">
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Menu' => 'admin/menu', 'Edit' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Menu</span> - Edit
@stop


@section('content')
    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit Menu</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::model($menu,array('method' => 'POST', 'url' => '/admin/menu/'. $menu->id . '/update/', 'class' => 'form-horizontal')) !!}

                    @include('base::admin.Menu.partials.form')

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Save Menu Changes', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Add an item to the menu</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/menu-item/create', 'class' => 'form-horizontal')) !!}

                    <div class="form-group">
                        {!! Form::label('pageSelect', 'Use a created page ?:', ['class' => 'control-label col-lg-2']) !!}
                        <div class="col-lg-10">
                            {!! Form::select('pageSelect', $pages, '0', ['class' => 'form-control', 'id' => 'pageSelect', 'autocomplete' => 'false']) !!}
                            {!!inputError($errors, 'pageSelect')!!}
                        </div>
                    </div>

                    @include('base::admin.Menu.partials.item')

                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-10">
                            {!! Form::button('<i class="far fa-save"></i> Add Menu Item', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit Menu Items Position</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="dd">
                        {!! $itemList !!}
                    </div>
                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-10">
                            {!! Form::open(array('method' => 'POST', 'url' => '/admin/menu-item/position', 'class' => 'form-horizontal', 'id' => 'item-order')) !!}
                            {!! Form::hidden('menu_id', "$menu->id") !!}
                            {!! Form::button('<i class="far fa-save"></i> Save Position', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>


                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Edit selected menu item</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <div class="itemWorking">Getting Item ...</div>
                    <div class="itemDetails"></div>

                </div>
            </div>

        </div>
    </div>
@stop


