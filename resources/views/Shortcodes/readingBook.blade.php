<div class="col-md-4 col-md-offset-{{$offset}}">
    <div class="row">
        <div class="col-md-6">
            @if($currentBook->front_cover != '')
                <div class="col-md-12 text-center mb-20"><a href="{{ url('members/book/'.$book['book'].'/'.$book['chapter'].'/'.$book['page'])}}"><img src="{!! url($currentBook->front_cover) !!}" id="" class="img-responsive" style="max-height:200px;"></a></div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12" style="text-align: left;">
                    <a href="{{ url('members/book/'.$book['book'].'/'.$book['chapter'].'/'.$book['page'])}}">Continue Reading : {{$currentBook->title}}</a>
                </div>
                <div class="col-md-12 mt-10" style="text-align: left;">
                    <span class="" style="margin-top: 40px !important;"><a href="{{ url('members/book/markAsRead/'.$book['book'])}}"><i class="far fa-check"></i> Mark as read</a></span>
                </div>
            </div>
        </div>
    </div>
</div>