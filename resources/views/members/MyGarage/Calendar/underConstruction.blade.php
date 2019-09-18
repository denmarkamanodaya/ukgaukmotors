@extends('base::members.Template')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'My garage' => '/members/mygarage','My Calendar' => 'is_current']) !!}
@stop

@section('sidebarText')
@stop

@section('sidebarFooter')

@stop

@section('page-header')
    <span class="text-semibold">My Calendar</span>
@stop


@section('content')

            <div class="row" style=" padding-top: 280px; padding-bottom:280px;">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="cs-page-not-found">
                        <div class="cs-text">
                            <p>Sorry, but your calendar is not quite ready.</p>
                            <span class="cs-error"><em>Under Construction</em></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="cs-seprater-v1">
                        <span><i class="far fa-calendar cs-bgcolor"> </i></span>
                    </div>
                </div>
            </div>

@stop


