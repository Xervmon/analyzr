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

<script>
$( document ).ready(function() {
	alert('Dashboard loaded');
	nv.addGraph(function() {
    var chart = nv.models.multiBarChart()
      .transitionDuration(350)
      .reduceXTicks(true)   //If 'false', every single x-axis tick label will be rendered.
      .rotateLabels(0)      //Angle to rotate x-axis labels.
      .showControls(true)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
      .groupSpacing(0.1)    //Distance between each group of bars.
    ;

    chart.xAxis
        .tickFormat(d3.format(',f'));

    chart.yAxis
        .tickFormat(d3.format(',.1f'));

    d3.select('.chart svg')
        .datum(exampleData())
        .call(chart);

    nv.utils.windowResize(chart.update);

    return chart;
});

//Generate some nice data.
function exampleData() {
  return stream_layers(3,10+Math.random()*100,.1).map(function(data, i) {
    return {
      key: 'Stream #' + i,
      values: data
    };
  });
}
	
	
});
</script>
