@foreach(config('calendar.events') as $calKey =>$calevent)
    <span class="mr-5"><span class="calendardot" style="background-color: {{$calevent}}"></span> {{config('calendar.events_legend.'.$calKey)}}</span>
@endforeach
