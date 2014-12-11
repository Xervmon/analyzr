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
      <?php $accId = json_decode($account->credentials); ?>
      <option value="{{$accId->accountId}}">{{$accId->accountId}}</option>
    </select> 
  </div>
 </div>

<div class="col-md-2">
  <div class="form-group">
 <label for="StartDate">StartDate</label>
<input type="text" class="from_date form-control" id="StartDate" contenteditable="false">  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
   <label for="EndDate">EndDate</label>
<input type="text" class="to_date form-control" id="EndDate"  contenteditable="false">  </div>
 </div>

 <div class="col-md-3">
  <div class="form-group">
  <label class="control-label" for="ProductName">ProductName</label>
    <select id="ProductName" class="form-control">
    @foreach ($services as $key=>$value)
      <option value="{{$key}}">{{$key}}</option>
    @endforeach
    </select> 
  </div>
 </div>

<div class="col-md-3">
<button id="applyfilter" class="btn btn-default" style="margin-top:25px; margin-right: 5px;" onclick="applyfilter();">Apply Filter</button>

<button id="clearfilter" class="btn btn-default" style="margin-top:25px; " onclick="clearfilter();">Clear Filter</button>
</div>

</div>

<div class="col-md-12">
</br>
<div id="collections" >
</div>

<div id="filtercollections" >
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

	var data ='<?=json_encode($collections) ?>';

	$(document).ready(function() {

		if (!$.isArray(data)) {
        	data = JSON.parse(data);
        }
		$('#collections').append(convertJsonToTableSecurityGroups(data));

        

         var startDate = new Date('01/01/2012');
         var FromEndDate = new Date();
         var ToEndDate = new Date();

         ToEndDate.setDate(ToEndDate.getDate()+365);

         $('.from_date').datepicker({  
         weekStart: 1,
         startDate: '01/01/2012',
         endDate: FromEndDate, 
         autoclose: true
         })
           .on('changeDate', function(selected){
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.to_date').datepicker('setStartDate', startDate);
        }); 
        $('.to_date')
         .datepicker({
        
        weekStart: 1,
        startDate: startDate,
        endDate: ToEndDate,
        autoclose: true
        })
        .on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('.from_date').datepicker('setEndDate', FromEndDate);
        });

		
	});

        $(document).ajaxStart(function(){

         $("#ajaxSpinnerImage").show();
         $('#collections').hide();
         })
        .ajaxStop(function(){
          $("#ajaxSpinnerImage").hide();
        });

      function applyfilter(){
      	$('#collections').hide();
      	$('#filtercollections').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 500px;" width:100px; height="142px" width="142px">');
      	   AccountId = $('#AccountId').val();
    	   StartDate = $('#StartDate').val();
    	   EndDate = $('#EndDate').val();
    	   ProductName = $('#ProductName').val();
        	$.ajax({
			url:  "{{ URL::to('account/'.$account->id.'/FilterCollectionData') }}",
			data:{'AccountId':AccountId,'StartDate' : StartDate,'EndDate' : EndDate,'ProductName' : ProductName},
			cache: false
		  })
		  .done(function( response ) {
			console.log(response);
			if (!$.isArray(response)) {
            	response = JSON.parse(response);
            }
		$('#filtercollections').append(convertJsonToTableSecurityGroups(response));
		});
      }
        
      function clearfilter(){
        	var startdate=$('#StartDate').val();
        	var enddate=$('#EndDate').val();
        	if(startdate || enddate){
        		$('#StartDate').val("");
        		$('#EndDate').val("");
        		$('#filtercollections').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 500px;" width:100px; height="142px" width="142px">');
		    window.location.reload();
        	}
   
        }


	// $(document).ready(function() {
	// 	$.ajax({
	// 		url:  "{{ URL::to('account/'.$account->id.'/CollectionData') }}",
	// 		cache: false
	// 	})
	// 	.done(function( response ) {
	// 		console.log(response);
	// 		if (!$.isArray(response)) {
 //            	response = JSON.parse(response);
 //            }
	// 	$('#collections').append(convertJsonToTableSecurityGroups(response));
	// 	});
	// });
	 </script>
@stop