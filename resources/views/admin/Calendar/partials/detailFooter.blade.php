@if($event->cal_eventable)

    @if($event->cal_eventable instanceof App\Models\Dealers)
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-11">
                <span class="eventDetailsSubHeading mb-20">Auctioneer Details
                </span>
                <div class="col-lg-6"><i class="far fa-gavel mr-5"></i> <a target="_blank" href="{{ url('/admin/auctioneer/'.$event->cal_eventable->slug)}}">View {{$event->cal_eventable->name}}</a></div>
                <div class="col-md-6">
                    @if($event->meta->event_url)
                        <i class="far fa-link mr-5"></i> <a target="_blank" href="{{$event->meta->event_url}}">Event Information</a>
                    @endif
                </div>

            </div>

        </div>

    @endif

@endif