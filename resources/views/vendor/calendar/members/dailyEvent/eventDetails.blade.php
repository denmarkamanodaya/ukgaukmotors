
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-{{$event->slug}}">
            <div class="panel-title">
                <div class="col-md-2 eventStartTime">
                    @if($event->start_time == '00:00')
                        All Day
                    @else
                        <span class="timedEvent">{{$event->start_time}} to {{$event->end_time}}</span>
                    @endif
                </div>
                <div class="col-md-10">
                    <a class="accordion-toggle collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#{{$event->slug}}" aria-expanded="false" aria-controls="{{$event->slug}}">
                        @if($event->cal_eventable)
                            @if($event->cal_eventable->logo && $event->cal_eventable->logo != '')
                                <img style="max-height: 30px;" src="{{ url('images/dealers/'.$event->cal_eventable->id.'/thumb100-'.$event->cal_eventable->logo)}}">
                            @endif
                        @endif
                        {{$event->title}}
                    </a>
                </div>


            </div>
        </div>
        <div id="{{$event->slug}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{$event->slug}}">
            <div class="panel-body">
                @if($event->meta->event_image != '')
                <div class="cal_event_image" style="background: url({{ url($event->meta->event_image)}})"></div>
                @endif

                <div class="row">
                    <div class="col-md-1"><i class="far fa-bars fa-2x"></i></div>
                    <div class="col-md-11">
                        @include('members.MyGarage.Calendar.partials.detailHeading')
                        {!! $event->meta->description !!}
                    </div>
                </div>

                <div class="row mt-20">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="row">
                        <div class="col-md-1"><i class="far fa-clock fa-lg"></i></div>
                        <div class="col-md-10"><span class="eventDetailsSubHeading">Time</span>
                            @if($event->start_time == '00:00')
                                All Day
                            @else
                                <span class="timedEvent">{{$event->start_time}} to {{$event->end_time}}</span>
                            @endif
                        </div>
                    </div>
                    </div>
                    <div class="col-md-5">
                        @if($event->meta->address && $event->meta->address != '')
                            <div class="row">
                            <div class="col-md-1"><i class="far fa-map-marker-alt fa-lg"></i></div>
                            <div class="col-md-10"><span class="eventDetailsSubHeading">location</span>
                                {!! nl2br($event->meta->address)!!}
                            </div>
                            </div>
                        @endif
                    </div>
                </div>

                    @include('members.MyGarage.Calendar.partials.detailFooter')

                    @if($event->meta->latitude && $event->meta->latitude != '')
                        <div class="eventMapContainer"><div id="map_{{$event->id}}" style="width: 100%; height: 150px;"></div></div>

                        <script>
                            $('#{{$event->slug}}').on('shown.bs.collapse', function () {
                                initMap(Number({{$event->meta->latitude}}),Number({{$event->meta->longitude}}),"{{$event->id}}");
                            })
                        </script>
                    @endif



            </div>
        </div>
    </div>

