@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('security/portPreferences.preferences') }}}</h5>
		</div>
	</div>
</div>
<div class="media-block">
	<ul class="list-group">
		@if(!empty($portPreferences)) 
			@foreach ($portPreferences as $portPreference)
			<li class="list-group-item">
					<div class="media">
						@if(in_array($portPreference->status, array(Lang::get('account/account.STATUS_IN_PROCESS'), 
															Lang::get('account/account.STATUS_STARTED'))))
							<form class="pull-right" method="post" action="{{ URL::to('security/portPreferences/' . $portPreference->id . '/refresh') }}">
									<!-- CSRF Token -->
									<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
									<!-- ./ csrf token -->
									<button type="submit" class="btn btn-success pull-right" role="button"><span class="glyphicon glyphicon-refresh"></span></button>
							</form>
						@endif	
						<form class="pull-right" method="post" action="{{ URL::to('security/portPreferences/' . $portPreference->id . '/delete') }}">
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
							<!-- ./ csrf token -->
							<button type="submit" class="btn btn-warning pull-right" role="button"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
						<a href="{{ URL::to('security/portPreferences/' . $portPreference->id . '/edit') }}" class="btn btn-success pull-right" role="button"><span class="glyphicon glyphicon-edit"></span></a>
						<div class="media-body">
							<h4 class="media-heading">{{ String::title($portPreference->project) }} : {{ String::title($portPreference->name) }}</h4>
							<p>
								<span class="glyphicon glyphicon-calendar"></span> <!--Sept 16th, 2012-->{{{ $portPreference->created_at }}}
							</p>
							<p>
								<span title="Status">{{ UIHelper::getLabel($portPreference->status) }}</span>
								| 
								<a href="{{ URL::to('security/portPreferences/' . $portPreference->cloudAccountId . '/portInfo') }}"><span class="glyphicon glyphicon-check"></span></a>
						
							</p>
							
						</div>
					</div>
				</li>
			@endforeach
		@endif
	</ul>
	@if(empty($portPreferences) || count($portPreferences) === 0) 
		<div class="alert alert-info"> {{{ Lang::get('security/portPreferences.empty_no_preferences') }}}</div>
	@endif
</div>
<div>
<a href="{{ URL::to('security/portPreferences/create') }}" class="btn btn-primary pull-right" role="button">{{{ Lang::get('security/portPreferences.portPreference_add') }}}</a>
</div>

@stop
