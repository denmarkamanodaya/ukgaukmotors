@extends('base::frontend.Template')


@section('page_title')
Manage Subscriptions
@stop

@section('page_container_class', 'login-container')


@section('meta')
@stop

@section('page_js')
    <script type="text/javascript" src="{{ url('theme/4/assets/js/plugins/notifications/bootbox.min.js')}}"></script>
    <script>
        $('a.subnews').click(function(event) {
            event.preventDefault();
            var btnId = $(this).attr('id');
            $.ajax({
                url: $(this).attr('href'),
                success: function(response) {
                    bootbox.alert(response.successHtml);
                    $('#'+btnId).css('display','none');
                    $('#'+btnId).next('a').css('display','inline-block');
                }
            });
            return false; // for good measure
        });

        $('a.unsubnews').click(function(event) {
            event.preventDefault();
            var btnId = $(this).attr('id');
            $.ajax({
                url: $(this).attr('href'),
                success: function(response) {
                    bootbox.alert(response.content);
                    $('#'+btnId).css('display','none');
                    $('#'+btnId).prev('a').css('display','inline-block');
                }
            });
            return false; // for good measure
        });

        $('a.newsdetails').click(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                success: function(response) {
                    bootbox.alert({
                        title: response.title,
                        message: response.content
                    });
                }
            });
            return false; // for good measure
        });
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
@stop

@section('page-header')
    <span class="text-semibold">Newsletter</span> - Manage subscriptions
@stop


@section('content')
    <div class="page-section" style="margin-bottom:30px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    @if($newsletters->count() > 0)

                        <div class="row">
                            <h2>Manage Newsletter Subscriptions</h2>
                            @foreach($newsletters as $newsletter)
                                <div class="col-sm-6 col-md-4">
                                    <div class="thumbnail newsletter-box">
                                        @if($newsletter->featured_image)
                                            <div class="thumb">
                                                <img src="{!! $newsletter->featured_image !!}">
                                            </div>
                                        @endif
                                        <div class="caption">
                                            <h3>{{$newsletter->title}}</h3>
                                            <p>{{$newsletter->summary}}</p>
                                            <p>
                                                <a href="{!! url('newsletter/manage/details/'.$newsletter->slug) !!}" class="btn btn-primary newsdetails" role="button">Details</a>
                                                @if(is_subscribed($newsletter, $newsSubscriptions))
                                                    <a style="display: none" id='sub_{{$newsletter->slug}}' href="{!! url('members/newsletter/subscribe/'.$newsletter->slug) !!}" class="btn btn-success subnews" role="button">Subscribe</a>
                                                    <a id='unsub_{{$newsletter->slug}}' href="{!! url('newsletter/manage/unsubscribe/'.$newsletter->slug) !!}" class="btn btn-warning unsubnews" role="button">Unsubscribe</a>
                                                @else
                                                    <a id='sub_{{$newsletter->slug}}' href="{!! url('newsletter/manage/subscribe/'.$newsletter->slug) !!}" class="btn btn-success subnews" role="button">Subscribe</a>
                                                    <a style="display: none" id='unsub_{{$newsletter->slug}}' href="{!! url('newsletter/manage/unsubscribe/'.$newsletter->slug) !!}" class="btn btn-warning unsubnews" role="button">Unsubscribe</a>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @endif
                    <p><a class='btn btn-success' href="{!! url('/newsletter/manage/logout') !!}">Logout of Newsletter Management</a> </p>

                </div>
            </div>
        </div>
    </div>
@stop

