<div class="page-header">
	<div class="row">
		@include('site.dashboard_account_error')
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

			<div class="media-body bs-callout-danger">
				<ul class="list-group list-group-horizontal">

					<li class="list-group-item panel panel-status panel-success">
						<div class="panel-heading" style="text-align:center; color:black;">
							<i class="fa fa-usd"></i> Total Cost
						</div>
						<div class="panel-body text-center">

							@foreach($chartDataForAccounts['series'] as $key => $value)

							<?php $totalcost[] = $value; ?>

							@endforeach

							{{array_sum($totalcost)}} USD
						</div>
					</li>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<li class="list-group-item panel panel-status panel-danger">
						<div class="panel-heading" style="text-align:center; color:black;">
							<i class="fa fa-usd"></i> Predicted Cost
						</div>
						<div class="panel-body text-center">
							<?php 

							$Apnsum = 0;

							foreach ($chartDataForAccounts['drilldownSeries'] as $keys => $values) {

								$alls[$keys] = $values;

								$arr = $values -> data;

								if (is_array($arr) && (!empty($arr))) {

									foreach ($arr as $key1 => $value1) {

										if ($value1[0] == 'APN Fee')

											$Apnsum += $value1[1];

									}

								}

							}

							$cost      = array_sum($totalcost);
							$multiple  = 12;
							$Predicted = $cost - $Apnsum;

							?>

							{{$Predicted * $multiple}} USD
							
						</div>
					</li>

				</ul>
			</div>

			<p class="chart1"></p>

		</div>

		@else
		<div class="alert alert-info">
			{{{ Lang::get('account/account.empty_accounts') }}}
		</div>
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

	$(document).ready(function() {
		if (!$.isArray(data)) {
			data = JSON.parse(data);

		}
		columnDrilldown('.chart1', 'column', data);
	}); 
</script>