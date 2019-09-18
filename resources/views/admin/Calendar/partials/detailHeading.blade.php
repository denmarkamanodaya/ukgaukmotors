@if($event->cal_eventable)

    @if($event->cal_eventable instanceof App\Models\Dealers)

        <span class="eventDetailsHeading mb-20">
        @if($event->cal_eventable->logo && $event->cal_eventable->logo != '')
                <img style="max-height: 60px;" src="{{ url('images/dealers/'.$event->cal_eventable->id.'/thumb100-'.$event->cal_eventable->logo)}}">
        @endif
            {{$event->cal_eventable->name}}
            </span>

    @endif

@else
    <span class="eventDetailsHeading">Event Details</span>
@endif