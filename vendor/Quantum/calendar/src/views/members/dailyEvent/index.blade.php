@foreach($events as $event)
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
    @include('calendar::members.dailyEvent.eventDetails')
    </div>
@endforeach