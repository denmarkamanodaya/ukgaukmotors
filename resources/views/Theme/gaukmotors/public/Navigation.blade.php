<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                <div class="cs-logo">
                    <div class="cs-media">
                        <figure style="float: left; padding-right: 10px; border-right: 1px solid #444"><a href="{!! url('/') !!}"><img src="{{ url('/images/gauk-motors.png')}}" alt="" style="height: 20px;" /></a></figure>
                        <figure style="float: left; padding-left: 10px; padding-right: 10px; border-right: 1px solid #444"><a href="https://gaukmotors.co.uk/motorpedia"><img src="{{ url('/images/motorpedia.png')}}" alt="" style="height: 20px" /></a></figure>
                        <figure style="float: left; padding-left: 10px"><a href="https://motorbuzz.gaukmotors.com"><img src="{{ url('/images/motorbuzz.png')}}" alt="" style="height: 20px" /></a></figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="cs-main-nav pull-right">
                    <nav class="main-navigation">
                            {!! Menu::show('Main Public Menu') !!}
                            <li class="cs-user-option">
                                <div class="cs-login">
                                    <!-- Modal -->
                                    @include(Theme::includeFile('Modal-Register','public'))
                                    @include(Theme::includeFile('Modal-Login','public'))
                                    @include(Theme::includeFile('Modal-Forgot','public'))
                                </div>
                            </li>
                        </ul>
                    </nav>

                    <div class="cs-user-option hidden-lg visible-sm visible-xs">
                        <div class="cs-login">
                            <!-- Modal -->
                            @include(Theme::includeFile('Modal-Register-Sm','public'))
                            @include(Theme::includeFile('Modal-Login-Sm','public'))
                            @include(Theme::includeFile('Modal-Forgot-Sm','public'))
                        </div>                        
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <nav class="main-navigation-z" style="display: block !important">
                    <ul class="">
                        <li><a href="/register"><i class="0 position-left"></i> Free Registration</a></li><li><a href="/login"><i class="far fa-sign-in position-left"></i> Login</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</header>
