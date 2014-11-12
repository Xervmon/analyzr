@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('security_account/security_account.your_accounts') }}}</h5>
		</div>
	</div>
</div>

<div class="media-block">
	<ul class="list-group">
		@if(!empty($accounts)) 
			@foreach ($accounts as $account)
	  			<li class="list-group-item">
					<div class="media">
						<span class="pull-left" href="#">
						    <img class="media-object img-responsive" src="{{ asset('/assets/img/providers/'.Config::get('provider_meta.'.$account->cloudProvider.'.logo')) }}" alt="{{ $account->cloudProvider }}" />
						</span>
						@if($account->status == Lang::get('security_account/security_account.STATUS_IN_PROCESS'))
							<form class="pull-right" method="post" action="{{ URL::to('security_account/' . $account->id . '/refresh') }}">
									<!-- CSRF Token -->
									<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
									<!-- ./ csrf token -->
									<button type="submit" class="btn btn-success pull-right" role="button"><span class="glyphicon glyphicon-refresh"></span></button>
							</form>
						@endif	
						<form class="pull-right" method="post" action="{{ URL::to('security_account/' . $account->id . '/delete') }}">
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
							<!-- ./ csrf token -->
							<button type="submit" class="btn btn-warning pull-right" role="button"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
						<a href="{{ URL::to('security_account/' . $account->id . '/edit') }}" class="btn btn-success pull-right" role="button"><span class="glyphicon glyphicon-edit"></span></a>
						<div class="media-body">
							<h4 class="media-heading">{{ String::title($account->name) }}</h4>
							<p>
								<span class="glyphicon glyphicon-calendar"></span> <!--Sept 16th, 2012-->{{{ $account->created_at }}}
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
		<div class="alert alert-info"> {{{ Lang::get('security_account/security_account.empty_accounts') }}}</div>
	@endif
</div>
<div>
<a href="{{ URL::to('security_account/create') }}" class="btn btn-primary pull-right" role="button">{{{ Lang::get('security_account/security_account.add_account') }}}</a>
</div>

@stop
