<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="cs-logo">
                    <div class="cs-media">
                        <div class="cs-media">
                        <figure style="float: left; padding-right: 10px; border-right: 1px solid #444"><a href="{!! url('/') !!}"><img src="{{ url('/images/gauk-motors.png')}}" alt="" style="height: 20px;" /></a></figure>
                        <figure style="float: left; padding-left: 10px; padding-right: 10px; border-right: 1px solid #444"><a href="https://gaukmotors.co.uk/motorpedia"><img src="{{ url('/images/motorpedia.png')}}" alt="" style="height: 20px" /></a></figure>
                        <figure style="float: left; padding-left: 10px"><a href="https://motorbuzz.gaukmotors.com"><img src="{{ url('/images/motorbuzz.png')}}" alt="" style="height: 20px" /></a></figure>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="cs-main-nav pull-right">
                    <nav class="main-navigation">
                        @if(!isset($page->hideMenu))
                            {!! Menu::show('Main Members Menu') !!}
                            <li class="cs-user-option">
                                <div class="cs-login">
                                    <div class="cs-login-dropdown"> <a href="#">
                                                <span class="navprofile">{!! show_profile_pic($user, 40, '') !!}</span>
                                            {{ $user->profile->first_name }}&nbsp;&nbsp;<i class="fas fa-caret-down"></i></a>
                                        <div class="cs-user-dropdown"> <strong>Hello {{ $user->profile->first_name }}</strong>
                                            <ul>
                                                <li><a href="{!! url('/members/profile') !!}"><i class="fas fa-user"></i> Your Profile</a></li>
                                                @if(Auth::user()->hasRole(Settings::get('main_content_role')))<li><a href="{!! url('/members/mygarage/shortlist') !!}"><i class="icon-heart"></i> Your Shortlist</a></li>@endif
                                                @if(Auth::user()->hasRole(Settings::get('main_content_role')))<li><a href="{!! url('/members/mygarage/feed') !!}"><i class="icon-taxi"></i> Your Car Feed</a></li>@endif
                                                <li><a href="{!! url('/members/support') !!}"><i class="far fa-life-ring"></i> Support</a></li>
                                            </ul>
                                            <a class="btn-sign-out" href="{!! url('/logout') !!}"><i class="far fa-sign-out"></i> Logout</a> </div>
                                    </div>
                                </div>
                            </li>
                        @else
                            @if($page->hideMenu == '0')
                                {!! Menu::show('Main Members Menu') !!}
                                <li class="cs-user-option">
                                    <div class="cs-login">
                                        <div class="cs-login-dropdown"> <a href="#">@if($user->profile->picture)
                                                    <span class="navprofile">{!! show_profile_pic($user, 40, '') !!}</span>
                                                @endif {{ $user->profile->first_name }}&nbsp;&nbsp;<i class="far fa-caret-down"></i></a>
                                            <div class="cs-user-dropdown"> <strong>Hello {{ $user->profile->first_name }}</strong>
                                                <ul>
                                                    <li><a href="{!! url('/members/profile') !!}"><i class="far fa-user"></i> Your Profile</a></li>
                                                    @if(Auth::user()->hasRole(Settings::get('main_content_role')))<li><a href="{!! url('/members/mygarage/shortlist') !!}"><i class="icon-heart"></i> Your Shortlist</a></li>@endif
                                                    @if(Auth::user()->hasRole(Settings::get('main_content_role')))<li><a href="{!! url('/members/mygarage/feed') !!}"><i class="icon-taxi"></i> Your Car Feed</a></li>@endif
                                                    <li><a href="{!! url('/members/support') !!}"><i class="far fa-life-ring"></i> Support</a></li>
                                                </ul>
                                                <a class="btn-sign-out" href="{!! url('/logout') !!}"><i class="far fa-sign-out"></i> Logout</a> </div>
                                        </div>
                                    </div>
                                </li>
                            @else
                            <ul><li><a href="#" style="color: #000">.</a> </li></ul>
                            @endif
                        @endif

                    </nav>
                </div>

            </div>
        </div>
    </div>
</header>