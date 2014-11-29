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
                      <div class="col-md-6">
                       <p class="chart1">
							<svg style="height:500px;">
							</svg>
						</p>
					</div>

        </div>
</div>

<script src="{{asset('assets/js/nvd3/lib/d3.v2.min.js')}}"></script>
<script src="{{asset('assets/js/nvd3/nv.d3.min.js')}}"></script>
<script src="{{asset('assets/js/nvd3/lib/stream_layers.js')}}"></script>
<script src="{{asset('assets/js/xervmon/charts.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/css/nvd3/nv.d3.min.css')}}">
<script>
var data = '{{json_encode($accounts)}}';
	$( document ).ready(function() 
	{
		if (!$.isArray(data)) 
		{
	    	data = JSON.parse(data);
	    }
	    for
		console.log('before chart');
		multibar();
	});
</script>