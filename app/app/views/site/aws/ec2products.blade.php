@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('EC2Products'))

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5> {{{ Lang::get('site.EC2Products') }}}</h5>
		</div>
	</div>
</div>

<div id="ec2Products">
</div>


@stop


{{-- Scripts --}}
@section('scripts')
    <script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
	<script type="text/javascript">
	var data ='<?=json_encode($ec2Products) ?>';
	$(document).ready(function() {
		if (!$.isArray(data)) {
        	data = JSON.parse(data);
        }
		$('#ec2Products').append(convertJsonToTableSecurityGroups(data));
		
	});
	</script>
@stop