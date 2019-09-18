

<script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChartTicket);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChartTicket() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Role');
        data.addColumn('number', 'Total');
        data.addRows([
            ['Open',     {{$tickets['open']}}],
            ['Replied',      {{$tickets['replied']}}],
            ['Awaiting Reply',      {{$tickets['awaiting_reply']}}],
            ['User Replied',      {{$tickets['user_replied']}}],
            ['Staff Replied',      {{$tickets['staff_replied']}}],
            ['Closed',  {{$tickets['closed']}}]
        ]);

        // Set chart options
        var options = {'title':'Support Tickets',
            is3D: true};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('ticket_chart_div'));
        chart.draw(data, options);
    }
    $(window).resize(function(){
        drawChartTicket();
    });
</script>

    <div class="col-md-12"><div class="" id="ticket_chart_div"></div></div>
    <div class="col-md-12">

        <h2><i class="icon-ticket text-blue-400" style=""></i> Latest 5 Tickets</h2>
        @if($tickets['latest']->count() > 0)
            @foreach($tickets['latest'] as $ticket)
            <div class="col-md-12 text-center"><a href="{!! url('/admin/tickets/ticket/'.$ticket->id) !!}">{{$ticket->title}}</a></div>
            <div class="col-md-12 text-center mb-10">{{$ticket->updated_at->diffForHumans()}}</div>
            @endforeach
        @else
            <div class="col-md-12 text-center">No Tickets</div>
        @endif
        <div class="col-md-12 text-center mt-20"><a href="{!! url('/admin/tickets/') !!}">View All Tickets</a></div>
    </div>


