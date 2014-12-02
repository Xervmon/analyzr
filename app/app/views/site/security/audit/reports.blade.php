@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5><a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }} </a> :{{{ Lang::get('security/audit.reports') }}}</h5>
				
		</div>
	</div>
</div>

<div id="auditReports">
	
</div>


@stop


{{-- Scripts --}}
@section('scripts')
    <script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
	<script type="text/javascript">
	var data ='<?=json_encode($reports) ?>';
	$(document).ready(function() {
		if (!$.isArray(data)) {
        	data = JSON.parse(data);
        }
        $('#auditReports').append(convertJsonToTableAuditReports(data));
	});
	</script>
@stop