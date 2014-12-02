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

<div id="costbarchart">
</div>
<div id="currentcostbarchart">
</div>

@stop


{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/js/Highcharts-4.0.4/js/highcharts.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/exporting.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/data.js')}}"></script>
<script src="{{asset('assets/js/Highcharts-4.0.4/js/modules/drilldown.js')}}"></script>
<script src="{{asset('assets/js/xervmon/charts2.js')}}"></script>

<script>
var result='';
var costchartsdata = '{{json_encode($costchartsdata)}}';
var currentcostchartsdata='{{json_encode($currentcostchartsdata)}}';
var chartdata = '{{json_encode($chartdata)}}';
var accountId='{{$account->id}}';
	$(document).ready(function() 
	{
		if (!$.isArray((costchartsdata)&&(currentcostchartsdata))) 
		{
			costchartsdata = JSON.parse(costchartsdata);
			currentcostchartsdata = JSON.parse(currentcostchartsdata);
	    	chartdata = JSON.parse(chartdata);
	    }
	    result=costchartsdata.drilldownSeries[0];
	    barchart('#costbarchart', 'bar', chartdata ,result);
	    for (index = 0; index < currentcostchartsdata.drilldownSeries.length; ++index) 
	    {
	     if(currentcostchartsdata.drilldownSeries[index].accountId==accountId){
	     	result=currentcostchartsdata.drilldownSeries[index];
	     	//currentcostbarchart('#currentcostbarchart', 'bar', chartdata ,result);
	     	 barchart('#currentcostbarchart', 'bar', chartdata ,result);
	     }
	    }
	});
</script>
@stop