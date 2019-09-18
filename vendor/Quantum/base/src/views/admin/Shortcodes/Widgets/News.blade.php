<!-- News panel -->
@if(count($news) > 0)
    <div class="panel panel-flat">
        <div class="panel-body">
            @foreach($news as $newsItem)
                @foreach($newsItem->roles as $role)
                    @if(Auth::user()->hasRole($role->name))
                        <div class="row news-item">
                            <div class="col-md-12">
                                <h6 class="text-semibold">{!! UserShortcode::membersPage($newsItem->title) !!}</h6>
                                <p class="content-group">{!! UserShortcode::membersPage($newsItem->content) !!}</p>
                            </div>
                        </div>
                        @break
                    @endif
                @endforeach

            @endforeach
        </div>
    </div>
@endif
<!-- /news panel -->