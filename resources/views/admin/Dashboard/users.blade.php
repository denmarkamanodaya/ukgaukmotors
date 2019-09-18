

<script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChartUserRegister);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Role');
        data.addColumn('number', 'Total');
        data.addRows([
            ['Admin',     {{$userCount['admin']}}],
            ['Members',      {{$userCount['members']}}],
            ['Premium',  {{$userCount['premium']}}],
            ['Premium Auctions',  {{$userCount['premium-auctions']}}]
        ]);

        // Set chart options
        var options = {'title':'Registered Users',
            is3D: true};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    function drawChartUserRegister() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Day');
        data.addColumn('number', 'Total');
        data.addRows([
                @foreach($registeredDates as $date =>$count)
            ['{{$date}}',     {{$count}}],
            @endforeach
        ]);

        // Set chart options
        var options = {'title':'Daily Register Count',
            hAxis: {
                title: 'Day'
            },
            legend: {position: 'none'},
            pointSize: 5
        };

        // Instantiate and draw our chart, passing in some options.

        var chart = new google.visualization.LineChart(document.getElementById('userRegister_chart_div'));

        chart.draw(data, options);
    }

    $(window).resize(function(){
        drawChart();
        drawChartVehicleImportCount();

    });

</script>

    <div class="col-md-12"><div class="" id="chart_div"></div></div>
    <div class="col-md-12">

        <h2><i class="icon-users4 text-blue-400" style=""></i> Total Users</h2>
        <h4>{!! $userCount['total'] !!}</h4>
    </div>
    <div class="col-md-12"><div class="" id="userRegister_chart_div"></div></div>


