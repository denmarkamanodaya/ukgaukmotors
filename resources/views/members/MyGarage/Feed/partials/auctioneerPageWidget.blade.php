<div class="widget myGarageVehicleWidget cs-category-link-icon">
    <img class="img-responsive" src="{!! url('images/mygarage.jpg') !!}">
    <h6>My Garage</h6>
    <ul>
        <li>
            @if(!hasAuctioneerFeed($dealer->slug, $user->garageFeed))
                {!! Form::open(array('method' => 'POST', 'url' => '/members/mygarage/feed/addFeed', 'class' => 'addFeedForm', 'id' => 'addFeedAuctioneer')) !!}
                {!! Form::hidden('auctioneer', $dealer->slug) !!}
                {!! Form::hidden('title', $dealer->name) !!}
                {!! Form::close() !!}
                <a class="addFeedAuctioneerSubmit" href="#"><i class="cs-color fas fa-car"></i> Add a {{$dealer->name}} feed</a>
                <a class="FeedAuctioneerFound" href="#" style="display: none"><i class="cs-color far fa-check"></i>{{$dealer->name}} feed found</a>
            @else
                <a class="FeedAuctioneerFound" href="#"><i class="cs-color far fa-check"></i>{{$dealer->name}} feed found</a>
            @endif
        </li>
    </ul>
</div>