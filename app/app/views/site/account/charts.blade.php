@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('ChartsData',$account->id))

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5><a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }} </a> : {{{ Lang::get('account/account.charts_data') }}}</h5>
		</div>
	</div>
</div>

<div id="currentcostbarchart">
</div>

<div>
<span class="glyphicon glyphicon-calendar"></span>
<span id="currentupdate">  Last Update  : </span>&nbsp;&nbsp;&nbsp;&nbsp;
<i class="fa fa-usd"></i><span id="currenttotalcost">  Total Cost  : </span>
</div>

</br>
</br>

<div id="costbarchart">
</div>

<div>
<span class="glyphicon glyphicon-calendar">
</span><span id="previousupdate">  Last Update  : </span>&nbsp;&nbsp;&nbsp;&nbsp;
<i class="fa fa-usd"></i><span id="previoustotalcost">  Total Cost  : </span>
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
var updatedate='';
var totalcost='';
var currentcostchartsdata='{{json_encode($currentcostchartsdata)}}';
var previouscostchartsdata = '{{json_encode($previouscostchartsdata)}}';

	$(document).ready(function() 
	{
		if (!$.isArray((currentcostchartsdata)&&(previouscostchartsdata))) 
		{

			currentcostchartsdata = JSON.parse(currentcostchartsdata);

            updatedate=currentcostchartsdata.result.drilldownSeries[0].updatedate;
            totalcost=currentcostchartsdata.result.drilldownSeries[0].totalcost;
			$('#currentupdate').append(updatedate);
			$('#currenttotalcost').append(totalcost+' USD');

			previouscostchartsdata = JSON.parse(previouscostchartsdata);

			updatedate=previouscostchartsdata.result.drilldownSeries[0].updatedate;
			totalcost=previouscostchartsdata.result.drilldownSeries[0].totalcost;
			$('#previousupdate').append(updatedate);
			$('#previoustotalcost').append(totalcost+' USD');

	    }
	    barchart('#currentcostbarchart', 'bar', currentcostchartsdata);
	    barchart('#costbarchart', 'bar', previouscostchartsdata);
	    
	   
	});
</script>
@stop