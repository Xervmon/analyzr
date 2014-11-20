@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('account/account.your_accounts') }}}</h5>
		</div>
	</div>
</div>

<?php

echo '<pre>';

print_r($summary);
die();
?>

@stop
