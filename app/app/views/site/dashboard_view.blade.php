<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>Dashboard</h5>
		</div>
	</div>
</div>

<div class="panel panel-default">
       
		
	<div class="panel-body">
    	<div class="col-md-12">
                       <p class="chart1">
							
					   </p>
					</div>

    </div>
</div>

<script src="{{asset('assets/js/Highcharts-4.0.4/js/highcharts.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/exporting.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/data.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/drilldown.js')}}"></script>
<script src="{{asset('assets/js/xervmon/charts2.js')}}"></script>
<script>
var data = '{{json_encode($accounts)}}';

	$( document ).ready(function() 
	{
		if (!$.isArray(data)) 
		{
	    	data = JSON.parse(data);
	    }
	    columnDrilldown('.chart1', 'column', data);
	});
	
	//var data = '{"series":{"Amazon Account-ReadOnly Profile":2265.75},"drilldownSeries":[{"id":"Amazon Account-ReadOnly Profile","name":"Amazon Account-ReadOnly Profile","data":[["Amazon Simple Email Service",0.01],["Amazon Virtual Private Cloud",35.65],["APN Annual Program Fee",2000],["Amazon Simple Storage Service",145.37],["Amazon RDS Service",5.16],["Amazon Simple Queue Service",0],["Amazon Simple Notification Service",0],["Amazon Elastic Compute Cloud",78.79],["AWS Key Management Service",0],["Amazon SimpleDB",0],["AWS Data Transfer",0.77]]}],"titleText":"Current Spend across all subscribed services","subtitleText":"Click the columns to view versions.","yAxisTitle":"Total Subscribed services"}';
	

	
});
</script>