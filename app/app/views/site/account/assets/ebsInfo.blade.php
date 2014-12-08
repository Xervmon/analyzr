@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('EbsInfo',$account->id))

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('account/account.awsVolumesDetails') }}} : <a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }} </a></h5>
		</div>
	</div>
</div>

<div id="instanceDetails">
</div>


@stop


{{-- Scripts --}}
@section('scripts')
    <script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
	<script type="text/javascript">
	var data ='<?=json_encode($instanceDetails) ?>';
	$(document).ready(function() {
		if (!$.isArray(data)) {
        	data = JSON.parse(data);
        }
		$('#instanceDetails').append(convertJsonToTableSecurityGroups(data));
		
	});
	</script>
@stop