@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('CollectionData',$account->id))
<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5><a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }}</a> : {{{ Lang::get('account/account.collection_data') }}}</h5>
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

	<div class="col-md-2">
		<div class="form-group">
			<label for="StartDate">StartDate</label>
			<input type="text" class="from_date form-control" id="StartDate" contenteditable="false">
		</div>
	</div>

	<div class="col-md-2">
		<div class="form-group">
			<label for="EndDate">EndDate</label>
			<input type="text" class="to_date form-control" id="EndDate"  contenteditable="false">
		</div>
	</div>

	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label" for="ProductName">ProductName</label>
			<select id="ProductName" class="form-control">
				@foreach ($services as $key=>$value)
				<option value="{{$key}}">{{$value}}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-md-4">
		<button id="applyfilter" class="btn btn-default" style="margin-top:25px; margin-right:25px;" onclick="applyFilter();">
			Apply Filter
		</button>
  
		<button id="clearfilter" class="btn btn-default" style="margin-top:25px; " onclick="clearFilter();">
			Clear Filter
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
    url:  "{{ URL::to('account/'.$account->id.'/CollectionData') }}",
    cache: false
  })
    .done(function( response ) {
    if (!$.isArray(response)) {
     response = JSON.parse(response);
    }
    $('#collections').html(convertJsonToTableSecurityGroups(response));
  });

  var startDate   = new Date('01/01/2012');
  var FromEndDate = new Date();
  var ToEndDate   = new Date();

  ToEndDate.setDate(ToEndDate.getDate()+365);

    $('.from_date').datepicker({
      weekStart : 1,
      startDate : '01/01/2012',
      endDate   : FromEndDate,
      autoclose : true
    })
     .on('changeDate', function(selected){
     startDate = new Date(selected.date.valueOf());
     startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
     $('.to_date').datepicker('setStartDate', startDate);
    });
     $('.to_date').datepicker({
       weekStart : 1,
       startDate : startDate,
       endDate   : ToEndDate,
      autoclose  : true
     })
      .on('changeDate', function(selected){
      FromEndDate = new Date(selected.date.valueOf());
      FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
      $('.from_date').datepicker('setEndDate', FromEndDate);

    });


});


function applyFilter(){
   $('#collections').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 500px;" height="90px" width="90px">');
    AccountId   = $('#AccountId').val();
    StartDate   = $('#StartDate').val();
    EndDate     = $('#EndDate').val();
    ProductName = $('#ProductName').val();
    $.ajax({
      url:  "{{ URL::to('account/'.$account->id.'/CollectionData') }}",
      data:{'AccountId':AccountId,'StartDate' : StartDate,'EndDate' : EndDate,'ProductName' : ProductName},
      cache: false
    })
     .done(function( response ) {
     if (!$.isArray(response)) {
         response = JSON.parse(response);
      }
     $('#collections').html(convertJsonToTableSecurityGroups(response));
    });
}

function clearFilter(){
    var startdate = $('#StartDate').val();
    var enddate   = $('#EndDate').val();
    if(startdate || enddate){
       $('#StartDate').val("");
       $('#EndDate').val("");
       $('#collections').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 500px;" height="90px" width="90px">');
       window.location.reload();
    }
}

</script>
@stop