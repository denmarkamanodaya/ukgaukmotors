<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="index.html"><img src="{!! url('/images/QheaderBase.png') !!}" alt=""></a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">


        <ul class="nav navbar-nav navbar-right">


            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    @if($user->profile->picture)
                        {!! show_profile_pic($user->profile->picture, 40, '', $user->username) !!}
                    @endif
                    <span>{!! $user->profile->first_name !!} {!! $user->profile->last_name !!}</span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="{!! url('members/profile') !!}"><i class="icon-user-plus"></i> My profile</a></li>
                    <li class="divider"></li>
                    <li><a href="{!! url('members/shortlist') !!}"><i class="icon-heart"></i> My Shortlist</a></li>
                    <li class="divider"></li>
                    <li><a href="{!! url('auth/logout') !!}"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Second navbar -->
<div class="navbar navbar-default" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
        @if(!isset($page->hideMenu))
            {!! Menu::show(2) !!}
        @else
            @if($page->hideMenu == '0')
                {!! Menu::show(2) !!}
            @endif
        @endif
    </div>


</div>
<!-- /second navbar -->