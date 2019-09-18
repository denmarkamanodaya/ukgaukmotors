@if(isset($feed->search) && $feed->search != '')
<li class="searchTermFeed"><i class="fas fa-search"></i> {{$feed->search}}</li>
@endif
@if(isset($feed->auctioneer) && $feed->auctioneer != '')
    <li class="searchTermFeed"><i class="fas fa-gavel"></i> {{$feed->auctioneerD->name or '' }}</li>
@endif
@if(isset($feed->location) && $feed->location != '')
    <li class="searchTermFeed"><i class="fas fa-map-marker"></i> {{$feed->location}}</li>
@endif
@if(isset($feed->vehicleMake) && $feed->vehicleMake != '')
    <li class="searchTermFeed"><i class="fas fa-car"></i> {{$feed->vehicleMakeD->name or ''}}</li>
@endif
@if(isset($feed->vehicleModel) && $feed->vehicleModel != '')

    <li class="searchTermFeed"><i class="fas fa-filter"></i> {{$feed->vehicleModelD->name or ''}}</li>
@endif
@if(isset($feed->auctionDay) && $feed->auctionDay != '')
    <li class="searchTermFeed"><i class="fas fa-calendar"></i> {{feedDate($feed->auctionDay)}}</li>
@endif