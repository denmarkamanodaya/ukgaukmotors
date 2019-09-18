<!-- News panel -->
@if(count($news) > 0)
<div class="panel panel-flat">
 <div class="panel-body">
        @foreach($news as $newsItem)
            @foreach($newsItem->roles as $role)
                @if($user->hasRole($role->name))
                 <h6 class="text-semibold">{!! UserShortcode::membersPage($newsItem->title) !!}</h6>
                 <p class="content-group">{!! UserShortcode::membersPage($newsItem->content) !!}</p>
                    @break
                @endif
            @endforeach

        @endforeach
    </div>
</div>
@endif
<!-- /news panel -->