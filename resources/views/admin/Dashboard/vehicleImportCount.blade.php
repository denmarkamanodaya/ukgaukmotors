

<script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChartVehicleImportCount);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChartVehicleImportCount() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Day');
        data.addColumn('number', 'Total');
        data.addRows([
            @foreach($importDates as $date =>$count)
            ['{{$date}}',     {{$count}}],
            @endforeach
        ]);

        // Set chart options
        var options = {'title':'Daily Vehicle Import Count',
            hAxis: {
                title: 'Day'
            },
            legend: {position: 'none'},
            pointSize: 5
        };

        // Instantiate and draw our chart, passing in some options.

        var chart = new google.visualization.LineChart(document.getElementById('vehicleImportCount_chart_div'));

        chart.draw(data, options);
    }
    $(window).resize(function(){
        drawChartVehicleImportCount();
    });
</script>

    <div class="col-md-12"><div class="" id="vehicleImportCount_chart_div"></div></div>



