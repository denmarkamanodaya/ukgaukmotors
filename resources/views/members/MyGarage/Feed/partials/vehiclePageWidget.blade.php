<div class="widget myGarageVehicleWidget cs-category-link-icon widgetAdjust">
    <img class="img-responsive" src="{!! url('images/mygarage.jpg') !!}">
    <h6>My Garage</h6>
    <ul>
        <li>

            @if(!hasAFeed($vehicle, $user->garageFeed))
            {!! Form::open(array('method' => 'POST', 'url' => '/members/mygarage/feed/addFeed', 'class' => 'addFeedForm', 'id' => 'addFeedMake')) !!}
                @if($vehicle->make && $vehicle->make->id != 1)
                {!! Form::hidden('vehicleMake', $vehicle->make->slug) !!}
                    {!! Form::hidden('title', $vehicle->make->name) !!}
                @else
                    {!! Form::hidden('search', getSearchWord($vehicle)) !!}
                    {!! Form::hidden('title', getSearchWord($vehicle)) !!}
                @endif

            {!! Form::close() !!}

                @if($vehicle->make && $vehicle->make->id != 1)
                    <a class="addFeedMakeSubmit" href="#"><i class="cs-color fas fa-car"></i> Add a {{$vehicle->make->name}} feed</a>
                    <a class="FeedMakeFound" href="#" style="display: none"><i class="cs-color far fa-check"></i>{{$vehicle->make->name}} feed found</a>
                @else
                    <a class="addFeedMakeSubmit" href="#"><i class="cs-color fas fa-car"></i> Add a {{getSearchWord($vehicle)}} feed</a>
                    <a class="FeedMakeFound" href="#" style="display: none"><i class="cs-color far fa-check"></i>{{getSearchWord($vehicle)}} feed found</a>
                @endif


            @else
                @if($vehicle->make && $vehicle->make->id != 1)
                <a class="FeedMakeFound" href="#"><i class="cs-color far fa-check"></i>{{$vehicle->make->name}} feed found</a>
                @else
                    <a class="FeedMakeFound" href="#"><i class="cs-color far fa-check"></i>{{getSearchWord($vehicle)}} feed found</a>
                    @endif
            @endif
        </li>
        @if($vehicle->model && $vehicle->model->id != 1)
        <li>
            @if(!hasMakeModelFeed($vehicle->make->slug, $vehicle->model->id, $user->garageFeed))
            {!! Form::open(array('method' => 'POST', 'url' => '/members/mygarage/feed/addFeed', 'class' => 'addFeedForm', 'id' => 'addFeedMakeModel')) !!}
            {!! Form::hidden('vehicleMake', $vehicle->make->slug) !!}
            {!! Form::hidden('vehicleModel', $vehicle->model->id) !!}
            {!! Form::hidden('title', $vehicle->make->name.' '.$vehicle->model->name) !!}
            {!! Form::close() !!}
            <a class="addFeedMakeModelSubmit" href="#"><i class="cs-color fas fa-car"></i> Add a {{$vehicle->make->name}} {{$vehicle->model->name}} feed</a>
            <a class="FeedMakeModelFound" href="#" style="display: none"><i class="cs-color far fa-check"></i>{{$vehicle->make->name}} {{$vehicle->model->name}} feed found</a>
            @else
                <a class="FeedMakeModelFound" href="#"><i class="cs-color far fa-check"></i>{{$vehicle->make->name}} {{$vehicle->model->name}} feed found</a>
            @endif
        </li>
        @endif
    </ul>
</div>