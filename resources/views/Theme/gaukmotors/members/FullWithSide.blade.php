<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEOMeta::generate() !!}
    <meta property="fb:pages" content="1027062620708425" />
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    <link rel="shortcut icon" href="{!! url('/images/GAUK-favicon.png') !!}" type="image/x-icon" />

    <link rel="stylesheet" href="{{ mix('assets/css/gauk.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/gaukMembers.css') }}">
    <link href="{{ url('assets/css/sidebar.css')}}" rel="stylesheet" type="text/css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('page_css')
    <script>var formErrors = []; var hideBreadcrumbs = false;</script>
</head>
<body class="wp-automobile @yield('body-class')">

<div class="wrapper">
    <!-- Header Start -->
@include(Theme::includeFile('Network', 'members'))
@include(Theme::navigation('members'))
<!-- Header End -->

<div class="page_wrap">
    @include('members.partials.sidebar')
    @yield('headerContent')
</div>




    <div class="main">
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="page-header page-section page-breadcrumbs-container">
                    <div class="page-header-content">
                        <div class="page-title">
                            @yield('breadcrumbs')
                        </div>

                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>













    <!-- Main End -->

</div>
    @include(Theme::includeFile('Footer', 'members'))
<script type='text/javascript' src="{{Theme::asset('scripts/jquery.js', 'public')}}"></script>
<script type='text/javascript' src="{{Theme::asset('scripts/modernizr.js', 'public')}}"></script>
<script type='text/javascript' src="{{Theme::asset('scripts/bootstrap.min.js', 'public')}}"></script>


<script type='text/javascript' src="{{Theme::asset('scripts/responsive.menu.js', 'public')}}"></script>
<script type='text/javascript' src="{{Theme::asset('scripts/chosen.select.js', 'public')}}"></script>
<script type='text/javascript' src="{{Theme::asset('scripts/slick.js', 'public')}}"></script>
<script type='text/javascript' src="{{Theme::asset('scripts/echo.js', 'public')}}"></script>
<script type='text/javascript' src="{{Theme::asset('scripts/functions.js', 'public')}}"></script>
<script type="text/javascript" src="{{ url('theme/4/assets/js/plugins/notifications/bootbox.min.js')}}"></script>
<script src="{{ url("assets/js/members-all.js") }}"></script>
@yield('page_js')
<script src="{{ url("assets/js/member.js") }}"></script>
@include('base::admin.partials.notifications')
@include('base::admin.partials.errors')
<script data-rocketsrc="https://emailoctopus.com/bundles/emailoctopuslist/js/formEmbed.js" type="text/rocketscript" data-rocketoptimized="true"></script><script type="text/javascript" src="https://emailoctopus.com/bundles/emailoctopuslist/js/formEmbed.js"></script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-589a43f8ae288f0c"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87940440-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>