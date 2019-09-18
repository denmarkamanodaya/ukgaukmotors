@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        var token = '{{ csrf_token() }}';
        var feedUrl = '{{ url('members/mygarage/getFeed') }}';
        var baseUrl = '{{ url('') }}';

        $('a.removeFeed').on('click', function(event){
            event.preventDefault();
            var location = $(this).attr('href');
            console.log(location);
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to remove this feed?',
                callback: function(result) {
                    if (result) {
                        window.location = location;
                    }
                }
            });
        });

    </script>
    <script src="{{ url("assets/js/garageFeed.js") }}"></script>
    <script type='text/javascript' src="{{ url('assets/js/feedSearch.js')}}"></script>
    <script type='text/javascript' src="{{ url('assets/js/bootstrap-tour.min.js')}}"></script>
    @if(!$seenWelcome)
    <script type='text/javascript' src="{{ url('assets/js/garageTour.js')}}"></script>
    @endif
    <script src="{{ url('assets/js/jquery_ui/full.min.js')}}"></script>
    <script type='text/javascript' src="{{ url('assets/js/shortlist.js')}}"></script>
    <script>
        $("#garageFeedAuction").sortable({
            items: ".feedCol",
            cursor: 'move',
            opacity: 0.6,
            connectWith: "#garageFeedAuction",
            forcePlaceholderSize: true,
            placeholder: "sortable-placeholder",
            update: function() {
                var info = $(this).sortable("serialize");
                console.log(info);
                var frm = $('form[id="changePosition"]')
                $.ajax({
                    type: "POST",
                    url: frm.attr('action'),
                    data: {position: info, _token: token},
                    success: function(msg){
                        console.log(msg);
                    }
                });
            }
        });

        $("#garageFeedClassified").sortable({
            items: ".feedCol",
            cursor: 'move',
            opacity: 0.6,
            connectWith: "#garageFeedClassified",
            forcePlaceholderSize: true,
            placeholder: "sortable-placeholder",
            update: function() {
                var info = $(this).sortable("serialize");
                console.log(info);
                var frm = $('form[id="changePosition2"]')
                $.ajax({
                    type: "POST",
                    url: frm.attr('action'),
                    data: {position: info, _token: token},
                    success: function(msg){
                        console.log(msg);
                    }
                });
            }
        });
    </script>

@stop

@section('page_css')
    <link href="{{ url('assets/css/garagefeed.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ url('assets/css/bootstrap-tour.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'My Garage' => '/members/mygarage', 'My Feed' => 'is_current']) !!}
@stop

@section('sidebarText')
    <div class="addFeedTitle">Add A Feed</div><div class="addFeedText">To add a new feed use the below form to set your requirements.</div>
    @include('members.MyGarage.Feed.partials.searchForm')
@stop

@section('sidebarFooter')
@stop

@section('page-header')
    <span class="text-semibold">My Feed</span>
@stop


@section('content')
    {!! Form::open(array('method' => 'POST', 'url' => '/members/mygarage/feed/changePosition', 'id' => 'changePosition')) !!}
    @if($user->garageFeed[1]->count() > 0)

        <div class="feedTotal">You are currently using {{$user->garageFeed[1]->count()}} out of your available 10 feed slots.</div>

        <div class="navbar navbar-default navbar-component navbar-xs">
            <ul class="nav navbar-nav visible-xs-block">
                <li class="full-width text-center"><a data-target="#navbar-filter" data-toggle="collapse"><i class="icon-menu7"></i></a></li>
            </ul>

            <div id="navbar-filter" class="navbar-collapse collapse">
                <ul class="nav navbar-nav element-active-slate-400">
                    <li class="active"><a data-toggle="tab" href="#auctionFeedTab" aria-expanded="false"><i class="fas fa-gavel position-left"></i> Auctions</a></li>
                    <li class=""><a data-toggle="tab" href="#classifiedFeedTab" aria-expanded="true"><i class="far fa-newspaper position-left"></i> Classifieds</a></li>
                </ul>

                <div class="navbar-right">

                </div>
            </div>
        </div>

        <div class="tabbable">
            <div class="tab-content">
                <div id="auctionFeedTab" class="tab-pane fade active in">

                    <div id="garageFeedAuction" class="garageFeedWrap">

                        <?php $i = 1 ?>
                        @foreach($user->garageFeed[1] as $feed)
                            {!! Form::hidden('position[]', $feed->id) !!}
                            <div class="feedCol {{isFirstFeed($i)}}" id="feedCol_{{$feed->id}}" data-search="{{$feed->search}}" data-feedId="{{$feed->id}}">

                                <div class="panel panel-default">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">{{str_limit($feed->title, 30)}}
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle tourWhiteColour" data-toggle="dropdown" aria-expanded="false"><i class="far fa-bars"></i> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <div class="titleChangeWrap">
                                                            <button class="btn btn-primary"><a href="#" data-toggle="modal" data-target="#titleModal_{{$feed->id}}"><i class="far fa-pencil"></i> Change Title</a></button>
                                                        </div>
                                                        <div class="deleteFeedWrap">

                                                            <a id="removeFeed" class="btn btn-danger removeFeed" href="{{ url('members/mygarage/feed/removeFeed/'.$feed->id)}}"><i class="far fa-times"></i> Remove Feed</a>
                                                        </div>
                                                        <li class="divider"></li>
                                                        <lh><h5 class="searchtermTitle">Search Terms</h5></lh>
                                                        @include('members.MyGarage.Feed.partials.searchTerms')
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body feedPanel scroll" id="feedBody_{{$feed->id}}">

                                    </div>

                                </div>


                            </div>
                            <?php $i++; ?>
                        @endforeach
                            {!! Form::close() !!}
                            @foreach($user->garageFeed[1] as $feed)
                                @include('members.MyGarage.Feed.partials.titleModal', ['feed' => $feed])
                            @endforeach
                    </div>

                </div>




                <div id="classifiedFeedTab" class="tab-pane fade">
                    {!! Form::open(array('method' => 'POST', 'url' => '/members/mygarage/feed/changePosition', 'id' => 'changePosition2')) !!}
                    @if(isset($user->garageFeed[2]) && $user->garageFeed[2]->count() > 0)
                        <div id="garageFeedClassified" class="garageFeedWrap">
                            <?php $i = 1 ?>
                            @foreach($user->garageFeed[2] as $feed)
                                {!! Form::hidden('position[]', $feed->id) !!}
                                <div class="feedCol {{isFirstFeed($i)}}" id="feedCol_{{$feed->id}}" data-search="{{$feed->search}}" data-feedId="{{$feed->id}}">

                                    <div class="panel panel-default">
                                        <!-- Default panel contents -->
                                        <div class="panel-heading">{{str_limit($feed->title, 30)}}
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle tourWhiteColour" data-toggle="dropdown" aria-expanded="false"><i class="far fa-bars"></i> <span class="caret"></span></a>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <div class="titleChangeWrap">
                                                                <button class="btn btn-primary"><a href="#" data-toggle="modal" data-target="#titleModal_{{$feed->id}}"><i class="far fa-pencil"></i> Change Title</a></button>
                                                            </div>
                                                            <div class="deleteFeedWrap">

                                                                <a id="removeFeed" class="btn btn-danger removeFeed" href="{{ url('members/mygarage/feed/removeFeed/'.$feed->id)}}"><i class="far fa-times"></i> Remove Feed</a>
                                                            </div>
                                                            <li class="divider"></li>
                                                            <lh><h5 class="searchtermTitle">Search Terms</h5></lh>
                                                            @include('members.MyGarage.Feed.partials.searchTerms')
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body feedPanel scroll" id="feedBody_{{$feed->id}}">

                                        </div>

                                    </div>


                                </div>
                                <?php $i++; ?>
                            @endforeach
                            {!! Form::close() !!}

                                @foreach($user->garageFeed[2] as $feed)
                                    @include('members.MyGarage.Feed.partials.titleModal', ['feed' => $feed])
                                @endforeach

                        </div>
                    @endif


                </div>
            </div>
        </div>




    @endif
@stop


