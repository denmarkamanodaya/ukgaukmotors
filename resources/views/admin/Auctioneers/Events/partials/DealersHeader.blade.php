<div class="panel panel-flat border-top-info border-bottom-info">
    <div class="panel-heading">
        <h6 class="panel-title">Auctioneer / Dealer Event</h6>
    </div>
    <div class="panel-body">
        This event is for <a target="_blank" href="/admin/auctioneer/{{$dealer->slug}}">{{$dealer->name}}</a>
        @if($dealer->website && $dealer->website != '')
        <a target="_blank" href="{{$dealer->website}}"><i class="far fa-link ml-20"></i> Visit Site</a>
        @endif
        <br>
    </div>
</div>