<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}

    <link rel="shortcut icon" href="{{url('assets/images/favicon.ico')}}" type="image/x-icon" />
    @yield('meta')

            <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{url('theme/4/assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('theme/4/assets/css/icons/fontawesome/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('theme/4/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('theme/4/assets/css/core.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('theme/4/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('theme/4/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('assets/css/members.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    @yield('page_css')

    <script>
        var formErrors = [];
        var baseUrl = '{!! url('/') !!}';
        var csrf_token = '{!! csrf_token() !!}';
    </script>

</head>

<body>
@include('members.Navigation')
        <!-- Page header -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> @yield('page-header')</h4>

            @yield('breadcrumbs')
        </div>

    </div>
</div>
<!-- /page header -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">
            @yield('content')

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {!! date('Y') !!}. Quantum Scripts
    </div>
    <!-- /footer -->

</div>
<!-- /page container -->
<!-- Core JS files -->
<script type="text/javascript" src="{{url('theme/4/assets/js/core/libraries/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{url('theme/4/assets/js/core/libraries/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('theme/4/assets/js/plugins/ui/nicescroll.min.js')}}"></script>
<script type="text/javascript" src="{{url('theme/4/assets/js/plugins/ui/drilldown.js')}}"></script>
<script type="text/javascript" src="{{url('theme/4/assets/js/plugins/notifications/bootbox.min.js')}}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script type="text/javascript" src="{{url('theme/4/assets/js/core/app.js')}}"></script>
@yield('page_js')
<script src="{{ url("assets/js/members-all.js") }}"></script>
<!-- /theme JS files -->
@include('base::admin.partials.notifications')
@include('base::admin.partials.errors')
</body>
</html>
