<div class="page-header">
	<div class="row">
		@foreach($chartDataForAccounts['series'] as $row => $value)
			@if($value == 0)
				<div class="col-md12">
					<div class="alert alert-danger alert-block">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>Error</h4>
   						<?php echo $row . ' shows 0 total. Please double check the Account Id and credentials for the account ';?>
					</div>
				</div>
			@endif	
		@endforach
		<div class="col-md-9">
			<h5>Dashboard</h5>
		</div>
		<div class="col-md-3">
		<a href="{{ URL::to('account/create') }}" class="btn btn-primary pull-right" role="button">{{{ Lang::get('account/account.add_account') }}}</a>		
		</div>
	</div>
</div>


<div class="panel panel-default">
		
	<div class="panel-body">
    	
    	@if(!empty($chartDataForAccounts['drilldownSeries']))

    	<div class="col-md-12">

                    <p class="chart1">
					</p>

        </div>

		@else
       <div class="alert alert-info"> {{{ Lang::get('account/account.empty_accounts') }}}</div>
        @endif

    </div>

</div>

<script src="{{asset('assets/js/Highcharts-4.0.4/js/highcharts.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/exporting.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/data.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/drilldown.js')}}"></script>
<script src="{{asset('assets/js/xervmon/charts2.js')}}"></script>
<script>
var data = '{{json_encode($chartDataForAccounts)}}';

	$( document ).ready(function() 
	{
		if (!$.isArray(data)) 
		{
	    	data = JSON.parse(data);
	    	
	    }
	    columnDrilldown('.chart1', 'column', data);
	});
</script>