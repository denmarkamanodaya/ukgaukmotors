<!-- Sidebar -->
<div id="sidebar-wrapper" class="hidden-sm hidden-xs">
    <div class="sidebar">
        <img class="img-responsive" src="{!! url('/images/myGarageHeader2.png') !!}">
        {!! Menu::show('My Garage') !!}
    </div>
<div id="sidebarText" class="">@yield('sidebarText')</div>
<div id="sidebarFooter" class="">@yield('sidebarFooter')</div>
</div>
<!-- /#sidebar-wrapper -->