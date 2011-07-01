<head>
<script type="text/javascript" src="./js/flot/jquery.js"></script>
<script type="text/javascript" src="./js/flot/jquery.flot.js"></script>
<title>Stats</title>
</head>
<body>
<?php
include("global_stats.php");
?>
<div id="placeholder" style="width:600px;height:300px"></div>
	<?php //echo $datapoints2; ?>
<?php //echo $datapoints3; ?>
	<script type="text/javascript">
$(function () {
    var d1 = <?PHP echo $datapoints; ?>;
	var d2 = <?PHP echo $datapoints2; ?>;
	var d3 = <?PHP echo $datapoints3; ?>;
    
    var plot = $.plot($("#placeholder"),
           [ { data: d1, label: "Hashrate (GH)", color: "<?php if(isset($block)){echo "#000000";} else {echo $hr_color;} ?>"}, { data: d2, label: "Workers", color: "<?php echo $worker_color; ?>" } , { data: d3, label: "Block Found", color: "#000000"} ], {
               series: {
                   lines: { show: true },
                   points: { show: true }
				},
			   xaxis: {
						mode: "time",
						timeformat: "%H:%M<br>%m/%d"
				 },
				 yaxis: { max: <?php echo (max($workers) + 50); ?> , tickSize: 25},
               grid: { hoverable: true, clickable: true }
             }

);

    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
       
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;
				
				$("#tooltip").remove();
				var x = item.datapoint[0],
					y = item.datapoint[1];
					var time = new Date(x);
					var month = time.getMonth() + 1;
					var day = time.getDate() + 1;
					var hours = time.getUTCHours();
					var minutes = time.getMinutes() ;
					if (minutes < 10){
					minutes = "0" + minutes
					}
					var datetime = hours  + ':' + minutes + '  ' + month + '/' + day;
					if (item.series.label != "Block Found") {
						var content = "<center>" + item.series.label +" at <br>" + datetime + " =<br>" + y + "</center>";}
					else {
						var content = "<center>" + item.series.label +" at <br>" + datetime + "</center>"; }
				showTooltip(item.pageX, item.pageY,
							content);
			}
		}
            
        
    });

    
});
</script>
</body>
