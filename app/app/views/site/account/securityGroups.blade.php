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
    <script src="{{asset('bower_components/DataTables/media/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables-bootstrap.js')}}"></script>
    <script src="{{asset('assets/js/datatables.fnReloadAjax.js')}}"></script>
	<script type="text/javascript">
		
		$(document).ready(function() {
			$.ajax({
			  url:  "{{ URL::to('account/'.$account->id.'/getSecurityGroupsData') }}",
			  cache: false
			})
			.done(function( response ) {
			    $('#securityGroups').append(convertJsonToTableSecurityGroups(response);
			});
			
		});
	</script>
@stop