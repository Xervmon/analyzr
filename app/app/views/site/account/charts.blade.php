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

@if(empty($currentcostchartsdata['result']['drilldownSeries']))
<div class="bs-example">
    <div class="alert alert-danger alert-error">
       <span id="currentcostbarcharterror"></span>
    </div>
</div>
@endif

@if(empty($previouscostchartsdata['result']['drilldownSeries']))
<div class="bs-example">
    <div class="alert alert-warning">
        <span id="previouscostbarchartwarning"></span>
    </div>
</div>
@endif

</br>
<div id="currentcostbarchart">
</div>

<div>
<span id="currentupdate"></span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span id="currenttotalcost"></span>
</div>

</br>
</br>

<div id="previouscostbarchart">
</div>

<div>
<span id="previousupdate"></span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span id="previoustotalcost"></span>
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

var updatedate = '';
var totalcost  = '';
var currentcostchartsdata  = '{{json_encode($currentcostchartsdata)}}';
var previouscostchartsdata = '{{json_encode($previouscostchartsdata)}}';

	$(document).ready(function() 
	{
		if (!$.isArray(currentcostchartsdata)) 
		{
			currentcostchartsdata = JSON.parse(currentcostchartsdata);

             if (currentcostchartsdata.result.drilldownSeries != undefined && currentcostchartsdata.result.drilldownSeries.length > 0) 
		     {

              updatedate=currentcostchartsdata.result.drilldownSeries[0].updatedate;
              totalcost=currentcostchartsdata.result.drilldownSeries[0].totalcost;
			  $('#currentupdate').html('<span class="glyphicon glyphicon-calendar"></span> Last Update  : '+updatedate);
			  $('#currenttotalcost').html('<i class="fa fa-usd"></i>  Total Cost  : '+totalcost+' USD');

			  barchart('#currentcostbarchart', 'bar', currentcostchartsdata);

            }else{

              $('#currentcostbarcharterror').html(currentcostchartsdata.result);

            }
       
       }

        if (!$.isArray(previouscostchartsdata)) 
		{
			previouscostchartsdata = JSON.parse(previouscostchartsdata);

             if (previouscostchartsdata.result.drilldownSeries != undefined && previouscostchartsdata.result.drilldownSeries.length > 0) 
		     {

			  updatedate=previouscostchartsdata.result.drilldownSeries[0].updatedate;
			  totalcost=previouscostchartsdata.result.drilldownSeries[0].totalcost;
			  $('#previousupdate').html('<span class="glyphicon glyphicon-calendar"></span> Last Update  : '+updatedate);
			  $('#previoustotalcost').html('<i class="fa fa-usd"></i>  Total Cost  : '+totalcost+' USD');

              barchart('#previouscostbarchart', 'bar', previouscostchartsdata);

	         }else{
  
            $('#previouscostbarchartwarning').html(previouscostchartsdata.result);
	    }

	   } 
	    
	    
	   
	});
</script>
@stop


