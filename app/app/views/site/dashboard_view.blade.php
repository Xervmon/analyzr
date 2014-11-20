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
								
								<h4 class="media-heading"> 
									<a alt="{{ $account->name }}" title="{{ $account->name }}" href="{{ URL::to('account/'.$account->id.'/edit') }}" class="pull-left" href="#">
									{{ String::title($account->name) }}
									</a> 
								</h4> | <span class="glyphicon glyphicon-calendar"></span> <strong>Created Date</strong>:{{{ $account->created_at }}}
								| <span title="Status">{{ UIHelper::getLabel($account->status) }}</span>
								| <a href="{{ URL::to('account/' . $account->id . '/SecurityGroups') }}"><span class="glyphicon glyphicon-lock"></span></a>
								<p class="summary">
									
								</p>
								<p class="chart{{$account->id}}">
									<svg style="height:500px;width:800px">
										
									</svg>
									
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
<script src="{{asset('assets/js/xervmon/charts.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/css/nvd3/nv.d3.min.css')}}">
<script>
<?php
	$accArr = '';
	foreach($accounts as $account)
	{
		$accArr[] = $account->id;
	}
	$urlTemp = URL::to('account/%ID%/ChartData');
?>
var accounts = '<?=json_encode($accArr)?>';
var urlTemp = '<?=$urlTemp;?>';

$( document ).ready(function() 
{
	if (!$.isArray(accounts)) 
	{
    	accounts = JSON.parse(accounts);
    }
	for (index = 0; index < accounts.length; ++index) 
	{
    	var url= urlTemp.replace('%ID%', accounts[index]);
    	var selector = '.chart'+accounts[index] + ' svg';
    	$.ajax({
		url:  url,
		cache: false
		}).done(function( response ) {
			if (!$.isArray(response)) {
	        	response = JSON.parse(response);
	        }
	        if(response.status == 'error')
	        {
	        	selector.append(response.message); return;
	        }
	        str =   ' Last Updated :' + response.data['lastUpdated'] 
	        	    + '| Month :' + response.data['month'] 
	        	    + '| Total :' + response.data['total'] 
	        $('.summary').append(str);
			pieOrDonut(response.chart, selector, true, 'percent');
		});
   }
});
</script>