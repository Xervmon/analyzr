@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('PortPreferencesInfo'))

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5> {{{ Lang::get('security/portPreferences.portPreference_list') }}}</h5>
		</div>
	</div>
</div>

<div id="portDetails">
</div>


@stop


{{-- Scripts --}}
@section('scripts')
    <script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
	<script type="text/javascript">
	var data ='<?=json_encode($portDetails) ?>';
	$(document).ready(function() {
		if (!$.isArray(data)) {
        	data = JSON.parse(data);
        }
		$('#portDetails').append(convertJsonToTableSecurityGroups(data));
		
	});
	</script>
@stop