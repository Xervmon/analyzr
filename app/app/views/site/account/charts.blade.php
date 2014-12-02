@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5><a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }} </a> : {{{ Lang::get('account/account.charts_data') }}}</h5>
		</div>
	</div>
</div>

<div id="currentcostbarchart">

</div>
<p id="currentcostupdate"></p>
</br>
</br>
<div id="costbarchart">
</div>
<p id="costupdate"></p>

@stop


{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/js/Highcharts-4.0.4/js/highcharts.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/exporting.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/data.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/drilldown.js')}}"></script>
<script src="{{asset('assets/js/xervmon/charts2.js')}}"></script>

<script>
var str='';
var result='';
var currentcostchartsdata='{{json_encode($currentcostchartsdata)}}';
var costchartsdata = '{{json_encode($costchartsdata)}}';
console.log(currentcostchartsdata);console.log(costchartsdata);
	$(document).ready(function() 
	{
		if (!$.isArray(costchartsdata))
		{
			costchartsdata = JSON.parse(costchartsdata);
		}
		if (!$.isArray(currentcostchartsdata))
		{
			currentcostchartsdata = JSON.parse(currentcostchartsdata);
		}
		str='Last Updated : '+currentcostchartsdata.result.drilldownSeries[0].updated;
		
		$('#currentcostupdate').append(str);
		
		str='Last Updated : '+costchartsdata.result.drilldownSeries[0].updated;
		$('#costupdate').append(str);
		
	    barchart('#currentcostbarchart', 'bar', currentcostchartsdata);
	    barchart('#costbarchart', 'bar', costchartsdata);
	    
	   
	});
</script>
@stop