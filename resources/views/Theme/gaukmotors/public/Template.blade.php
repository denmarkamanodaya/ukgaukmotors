<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEOMeta::generate() !!}
    <meta property="fb:pages" content="1027062620708425x" />
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    @yield('meta')
    <link rel="shortcut icon" href="{!! url('/images/Motorsfavicon.ico') !!}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ mix('assets/css/gauk.css') }}">

    @if(Auth::check())
        <link href="{{ url('assets/css/members.css')}}" rel="stylesheet" type="text/css">
    @endif




    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('page_css')
<!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '236166706776057'); // Insert your pixel ID here.
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=236166706776057&ev=PageView&noscript=1"
        /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
    <script>var formErrors = []; var hideBreadcrumbs = false;</script>

</head>
<body class="wp-automobile @yield('body-class')">
<div class="wrapper">
    <!-- Header Start -->
    @include(Theme::includeFile('Network', 'members'))
    @if(Auth::check())
    @include(Theme::navigation('members'))
    @else
    @include(Theme::navigation('public'))
            @endif
    <!-- Header End -->

    <!-- Main Start -->
    <div class="main-section">
        @yield('pre-content')
        <div class="page-header page-section page-breadcrumbs-container">
            <div class="page-header-content container">
                <div class="page-title">
                    @yield('breadcrumbs')
                </div>

            </div>
        </div>
        @yield('headerContent')

        <div class="page-section" style="margin-top:20px;margin-bottom:30px;">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Main End -->

@include(Theme::includeFile('Footer', 'public'))

</div>

<script src="{{ mix('assets/js/gauk.js') }}"></script>
@yield('page_js')
<script src="{{ url("assets/js/ouibounce.js") }}"></script>
<script src="{{ url("assets/js/public.js") }}"></script>
<script src="{{ url("assets/js/newsletterSubscribe.js") }}"></script>

@include(Theme::includeFile('ouibounce', 'public'))
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87940440-1', 'auto');
    ga('send', 'pageview');

</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-589a43f8ae288f0c"></script>
</body>
</html>
