@extends('site.layouts.default')


{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render(Lang::get('breadcrumb/breadcrumb.CloudTrail'),$account->id))
<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5><a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }}</a> : {{{ Lang::get('account/account.CloudTrail') }}}</h5>
		</div>
	</div>
</div>

<div class="col-md-12">

	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label" for="AccountId">AccountId</label>
			<select id="AccountId" class="form-control">
				<?php $accId = json_decode($account -> credentials); ?>
				<option value="{{$accId->accountId}}">{{$accId->accountId}}</option>
			</select>
		</div>
	</div>

  <div class="col-md-3">
    <div class="form-group">
      <label class="control-label" for="EventName">EventName</label>
      <select id="EventName" class="form-control">
        @foreach ($eventName as $key=>$value)
        <option value="{{$value}}">{{$value}}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="col-md-3">
		<div class="form-group">
			<label class="control-label" for="EventType">EventType</label>
			<select id="EventType" class="form-control">
				@foreach ($eventType as $key=>$value)
				<option value="{{$value}}">{{$value}}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-md-4">
		<button id="applyfilter" class="btn btn-default" style="margin-top:25px; margin-right:25px;" onclick="applyFilter();">
			Apply Filter
		</button>
  
	</div>

</div>

<div class="col-md-12">

	</br>
	<div id="collections" > 
  </div>

</div>

@stop

{{-- Styles --}}
@section('styles')
<link rel="stylesheet" href="{{asset('assets/css/datepicker.css')}}">

{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/js/bootstrap/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/js/xervmon/utils.js')}}"></script>

<script type="text/javascript">

$(document).ready(function() {

  $('#collections').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 500px;" height="90px" width="90px">');
  $.ajax({
    url:  "{{ URL::to('account/'.$account->id.'/cloudTrailCollection') }}",
    cache: false
  })
    .done(function( response ) {
    if (!$.isArray(response)) {
     response = JSON.parse(response);
    }
    $('#collections').html(convertJsonToTableSecurityGroups(response));
  });

});


function applyFilter(){
   $('#collections').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 500px;" height="90px" width="90px">');
    AccountId   = $('#AccountId').val();
    EventName = $('#EventName').val();
    EventType = $('#EventType').val();
    $.ajax({
      url:  "{{ URL::to('account/'.$account->id.'/cloudTrailCollection') }}",
      data:{'AccountId':AccountId,'EventName' : EventName,'EventType' : EventType},
      cache: false
    })
     .done(function( response ) {
     if (!$.isArray(response)) {
         response = JSON.parse(response);
      }
     $('#collections').html(convertJsonToTableSecurityGroups(response));
    });
}

</script>
@stop