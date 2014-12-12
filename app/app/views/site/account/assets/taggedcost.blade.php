@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render(Lang::get('breadcrumb/breadcrumb.TaggedCost'),$account->id))

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('account/account.awsSecGroupsDetails') }}} : <a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }} </a> </h5>
		</div>
	</div>
</div>

<div id="taggedcost">
</div>


@stop


{{-- Scripts --}}
@section('scripts')
    <script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
	<script type="text/javascript">
	var data ='<?=json_encode($taggedcost) ?>';
	$(document).ready(function() {
		if (!$.isArray(data)) {
        	data = JSON.parse(data);
        }
		$('#taggedcost').append(convertJsonToTableSecurityGroups(data));
		
	});
	</script>
@stop