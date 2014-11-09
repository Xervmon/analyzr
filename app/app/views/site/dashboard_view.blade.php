<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>Dashboard</h5>
		</div>
	</div>
</div>

<div class="media-block">
	<ul class="list-group">
		@if(!empty($accounts)) 
			@foreach ($accounts as $account)
		  			<li class="list-group-item">
						<div class="media">
							<p>
								<a alt="{{ $account->name }}" title="{{ $account->name }}" href="{{ URL::to('account/'.$account->id.'/edit') }}" class="pull-left" href="#">
								    <img title="{{ $account->name }}" class="media-object img-responsive" src="{{ asset('/assets/img/providers/'.Config::get('provider_meta.'.$account->cloudProvider.'.logo')) }}" alt="{{ $account->name }}" />
								</a> 
							</p>
							<form class="pull-right" method="post" action="{{ URL::to('account/' . $account->id . '/refresh') }}">
								<!-- CSRF Token -->
								<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
								<!-- ./ csrf token -->
								<button type="submit" class="btn btn-success pull-right" role="button"><span class="glyphicon glyphicon-refresh"></span></button>
							</form>
							<div class="media-body">
								
								<h4 class="media-heading">{{ String::title($account->name) }} </h4> <span class="glyphicon glyphicon-calendar"></span> <strong>Created Date</strong>:{{{ $account->created_at }}}
								<p class="chart">
									<svg>
										
									</svg>
									
								</p>
								
								
								<p>
									<span title="Status">{{ UIHelper::getLabel($account->status) }}</span>
									| 
									<a href="{{ URL::to('account/' . $account->id . '/SecurityGroups') }}"><span class="glyphicon glyphicon-lock"></span></a>
								
								</p>
							</div>
						</div>
					</li>	
			@endforeach
		@endif
	</ul>
	@if(empty($accounts) || count($accounts) === 0) 
		<div class="alert alert-info"> {{{ Lang::get('account/account.empty_accounts') }}}</div>
	@endif
</div>

<script src="{{asset('assets/js/nvd3/lib/d3.v2.min.js')}}"></script>
<script src="{{asset('assets/js/nvd3/nv.d3.min.js')}}"></script>
<script src="{{asset('assets/js/nvd3/lib/stream_layers.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/css/nvd3/nv.d3.min.css')}}">
	
	

<script>

$( document ).ready(function() {
	//Donut chart example
	
	//Donut chart example
nv.addGraph(function() {
  var chart = nv.models.pieChart()
      .x(function(d) { return d.label })
      .y(function(d) { return d.value })
      .showLabels(true)     //Display pie labels
      .labelThreshold(.05)  //Configure the minimum slice size for labels to show up
      .labelType("percent") //Configure what type of data to show in the label. Can be "key", "value" or "percent"
      .donut(true)          //Turn on Donut mode. Makes pie chart look tasty!
      .donutRatio(0.35)     //Configure how big you want the donut hole size to be.
      ;

    d3.select(".chart2 svg")
        .datum(exampleData())
        .transition().duration(350)
        .call(chart);

  return chart;
});

   


});


//Pie chart example data. Note how there is only a single array of key-value pairs.
function exampleData() {
  return  [
      { 
        "label": "One",
        "value" : 29.765957771107
      } , 
      { 
        "label": "Two",
        "value" : 0
      } , 
      { 
        "label": "Three",
        "value" : 32.807804682612
      } , 
      { 
        "label": "Four",
        "value" : 196.45946739256
      } , 
      { 
        "label": "Five",
        "value" : 0.19434030906893
      } , 
      { 
        "label": "Six",
        "value" : 98.079782601442
      } , 
      { 
        "label": "Seven",
        "value" : 13.925743130903
      } , 
      { 
        "label": "Eight",
        "value" : 5.1387322875705
      }
    ];
}
	
	

</script>
