@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{ $account->name }} : {{{ Lang::get('account/account.securityGroups') }}}</h5>
		</div>
	</div>
</div>

<div id="securityGroups">
</div>

@stop


{{-- Scripts --}}
@section('scripts')
    <script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
	<script type="text/javascript">
		
		$(document).ready(function() {
			$.ajax({
			  url:  "{{ URL::to('account/'.$account->id.'/SecurityGroupsData') }}",
			  cache: false
			})
			.done(function( response ) {
			    $('#securityGroups').append(convertJsonToTableSecurityGroups(response));
			});
			
		});
	</script>
@stop