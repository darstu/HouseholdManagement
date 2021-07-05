<html>
<head>
    <title>laravel google line chart tutorial example - NiceSnippets.com</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<h2 style="margin:50px 0px 0px 130px;">Laravel Google Line Chart Tutorial Example - NiceSnippets.com</h2>
<div id="linechart" style="width: 900px; height: 500px;"></div>
<script type="text/javascript">
    var visitor = <?php echo $visitor; ?>;
    console.log(visitor);
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(visitor);
        var options = {
            title: 'Site Visitor Line Chart',
            curveType: 'function',
            legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('linechart'));
        chart.draw(data, options);
    }
</script>
</body>
</html>
