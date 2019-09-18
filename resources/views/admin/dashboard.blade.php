@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>

        loadWidget('users', 'user_widget');
        loadWidget('tickets', 'ticket_widget');
        loadWidget('vehicleCountLog', 'vehicleCountLog_widget');
        loadWidget('vehicleImportCount', 'vehicleImportCount_widget');
        function loadWidget(widget,target) {
            $.ajax({
                url: '{!! url('/admin/dashboard/ajax') !!}' + '/' + widget,
                type: "GET",
                success: function(data){
                    $data = $(data);
                    $('#'+target).fadeOut().html($data).fadeIn();
                }
            });
        }
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Admin</span> - Dashboard
@stop


@section('content')
    @include('admin.partials.dashboard_news')

    <div class="row">
        <div class="col-md-4">

            <div class="col-lg-12">
                <!-- Totals panel -->
                <div class="panel panel-flat">
                    <div class="panel-body text-center">
                        <div id="user_widget" class=""></div>
                    </div>
                </div>
                <!-- /totals panel -->
            </div>

            @foreach($newsletters as $newsletter)
                <div class="col-md-6">
                    <div class="thumbnail newsletter-box">
                        @if($newsletter->featured_image)
                            <div class="thumb">
                                <img src="{!! $newsletter->featured_image !!}" class="img-responsive">
                            </div>
                        @endif
                        <div class="caption">
                            <h3>{{$newsletter->title}}</h3>
                            <p>Subscribers : {{$newsletter->subscribers_count}}</p>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Col2-->
        <div class="col-md-4">
            <div class="col-md-12">
                <!-- Totals panel -->
                <div class="panel panel-flat">
                    <div class="panel-body text-center">
                        <div id="ticket_widget" class=""></div>
                    </div>
                </div>
                <!-- /totals panel -->
            </div>
        </div>

        <!-- Col3 -->
        <div class="col-md-4">

            <div class="col-md-12">
                <!-- Totals panel -->
                <div class="panel panel-flat">
                    <div class="panel-body text-center">
                        <div id="vehicleCountLog_widget" class=""></div>
                    </div>
                </div>
                <!-- /totals panel -->
            </div>

            <div class="col-md-12">
                <!-- Totals panel -->
                <div class="panel panel-flat">
                    <div class="panel-body text-center">
                        <div id="vehicleImportCount_widget" class=""></div>
                    </div>
                </div>
                <!-- /totals panel -->
            </div>

        </div>


    </div>









        @include('admin.partials.dashboard_totals')
@stop


