<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>Dashboard</h5>
		</div>
	</div>
</div>

<div class="panel panel-default">
       
		<?php
		echo '<pre>';
		print_r($accounts);
		
		?>
		
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
</script>