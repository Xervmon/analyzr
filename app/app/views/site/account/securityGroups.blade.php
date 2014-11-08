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

<?php
ech '<pre>';
print_r($securityGroups); die();
?>

@stop


{{-- Scripts --}}
@section('scripts')
   
@stop