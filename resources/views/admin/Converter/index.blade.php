@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $('#modeltomake').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Convert Confirmation',
                message: 'Are you sure you want to convert this vehicle model?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Converter' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Converter</span> - Convert Stuff !
@stop


@section('content')

    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Car Models</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    <h5>Convert a Model to a Make</h5>
                    <p>This will convert an existing Car Model to a New Car Make</p>
                    <p>Note: System imported models will not convert, only user created ones.</p>
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/convert/modeltomake', 'id' => 'modeltomake')) !!}
                    Select Model : {!! car_Model_input_list() !!}
                    <button type="submit" class="btn btn-info mt-10">Convert to a New Make with Description<i class="far fa-random position-right"></i></button>
                    {!! Form::close() !!}

                </div>
            </div>


        </div>

    </div>


@stop


