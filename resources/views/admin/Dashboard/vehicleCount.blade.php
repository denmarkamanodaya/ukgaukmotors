

<script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChartVehicleCount);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChartVehicleCount() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Day');
        data.addColumn('number', 'Total');
        data.addRows([
            @foreach($vehicleCount as $count)
            ['{{$count->created_at->format('jS')}}',     {{$count->total}}],
            @endforeach
        ]);

        // Set chart options
        var options = {'title':'Max Daily Vehicle Count',
            hAxis: {
                title: 'Day'
            },
            legend: {position: 'none'},
            pointSize: 5
        };

        // Instantiate and draw our chart, passing in some options.

        var chart = new google.visualization.LineChart(document.getElementById('vehicleCount_chart_div'));

        chart.draw(data, options);
    }
    $(window).resize(function(){
        drawChartVehicleCount();
    });
</script>

    <div class="col-md-12"><div class="" id="vehicleCount_chart_div"></div></div>



