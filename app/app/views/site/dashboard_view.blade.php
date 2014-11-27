<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>Dashboard</h5>
		</div>
	</div>
</div>

<div class="panel panel-default">
       
		@if(!empty($accounts)) 
			@foreach ($accounts as $account)

			<ul class="list-group">
		  			<li class="list-group-item">
						<div class="panel-heading">
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
								
								<h4> 
									<a alt="{{ $account->name }}" title="{{ $account->name }}" href="{{ URL::to('account/'.$account->id.'/edit') }}" class="pull-left" href="#">
									{{ String::title($account->name) }}
									</a> 
								</h4> 
								| <span class="glyphicon glyphicon-calendar"></span> <strong>Created Date</strong>:{{{ $account->created_at }}}
								@if($account -> profileType == Constants::READONLY_PROFILE)
								| <span title="Status">{{ UIHelper::getLabel($account->status) }}</span>
								| <a href="{{ URL::to('account/' . $account->id . '/SecurityGroups') }}"><span class="glyphicon glyphicon-lock"></span></a>
								| <a href="{{ URL::to('account/' . $account->id . '/AwsInfo') }}"><span class="glyphicon glyphicon-info-sign"></span></a>

								@else
								<span title="Status">{{ UIHelper::getLabel($account->status) }}</span>
								| 
								<a href="{{ URL::to('security/' . $account->id . '/auditReports') }}"><span class="glyphicon glyphicon-lock"></span></a>
                                @endif
                         </div>      
					</li>
				</ul>
					<div class="panel-body">
                      <div class="col-md-6">
                       <p class="chart{{$account->id}}">
							<svg style="height:500px;">
							</svg>
						</p>
					</div>
                <div class="col-md-2 column" >
                    <div class="list-group">
                        <div class="list-group-item" style="margin-top:-15px;height:12em;">
                              <p class="summary{{$account->id}}">
				                					
							  </p>
                        </div>
                    </div>
                </div>   
                 <div class="col-md-4">
                      
                  </div>

              </div>
			@endforeach
		@endif
</div>

	@if(empty($accounts) || count($accounts) === 0) 
		<div class="alert alert-info"> {{{ Lang::get('account/account.empty_accounts') }}}</div>
	@endif

<div>
<a href="{{ URL::to('account/create') }}" class="btn btn-primary pull-right" role="button">{{{ Lang::get('account/account.add_account') }}}</a>
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
		if($account->profileType == Constants::READONLY_PROFILE)
		{
			$accArr[] = $account;
		}
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
    	var url= urlTemp.replace('%ID%', accounts[index].id);
    	var selector = '.chart'+accounts[index].id + ' svg';
    	$.ajax({
		url:  url,
		cache: false
		}).done(function( response ) {
			if (!$.isArray(response)) {
				
	        	response = JSON.parse(response);console.log(response.id);
	        }
	        if(response.status == 'error')
	        {
	        	selector.append(response.message); return;
	        }
	        str =   ' Last Updated :' + response.data['lastUpdated'] 
	        	    + '| Month :' + response.data['month'] 
	        	    + '| Total :' + response.data['total'] 
	        	    
	        $('.summary'+response.id).append(str);
			pieOrDonut(response.chart, selector, true, 'percent');
		});
   }
});
</script>