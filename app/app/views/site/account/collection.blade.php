@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5><a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }}</a> : {{{ Lang::get('account/account.collection_data') }}}</h5>
		</div>
	</div>
</div>
<?php 

$acccollections=array();
for($i = 0; $i < count($collections); $i++){
$acccollections[] = $collections[$i]['accountId'];
}

$StartDatecollections=array();
for($i = 0; $i < count($collections); $i++){
$StartDatecollections[] = $collections[$i]['UsageStartDate'];
}

$EndDatecollections=array();
for($i = 0; $i < count($collections); $i++){
$EndDatecollections[] = $collections[$i]['UsageEndDate'];
}

$ProductNamecollections=array();
for($i = 0; $i < count($collections); $i++){
$ProductNamecollections[] = $collections[$i]['ProductName'];
}
?>

<div class="col-md-12">

<div class="col-md-3">

<div class="form-group">
  <label class="control-label" for="AccountId">AccountId</label>
    <select id="AccountId" class="form-control">
    <option value="">select</option>
    @foreach (array_unique($acccollections) as $key=>$value)
      <option value="{{$value}}">{{$value}}</option>
    @endforeach
    </select> 
  </div>
 </div>
<div class="col-md-3">
  <div class="form-group">
 <label for="startdate">StartDate</label>
  <input type="text" class="form-control" id="startdate"> 
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
   <label for="enddate">EndDate</label>
  <input type="text" class="form-control" id="enddate"> 
  </div>
 </div>

 <div class="col-md-3">
  <div class="form-group">
  <label class="control-label" for="ProductName">ProductName</label>
    <select id="ProductName" class="form-control">
    @foreach (array_unique($ProductNamecollections) as $key=>$value)
      <option value="{{$value}}">{{$value}}</option>
    @endforeach
    </select> 
  </div>
 </div>

</div>
<!-- <button id="submit" class="btn btn-default">Submit</button>
 -->



<div class="col-md-12">
</br>
<div id="collections" >

</div>

</div>

@stop

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

 $('#startdate').datepicker({
                    format: "dd/mm/yyyy"
                });

  $('#enddate').datepicker({
                    format: "dd/mm/yyyy"
                });
 

    $('#AccountId').change(function(){
    	AccountId = $('#AccountId').val();
    	StartDate = $('#StartDate').val();
    	EndDate = $('#EndDate').val();
    	ProductName = $('#ProductName').val();
        alert(ProductName);
        	$.ajax({
			url:  "{{ URL::to('account/'.$account->id.'/FilterCollectionData') }}",
			data:{'AccountId':AccountId,'StartDate' : StartDate,'EndDate' : EndDate,'ProductName' : ProductName},
			cache: false
		})
		// .done(function( response ) {
		// 	console.log(response);
		// 	if (!$.isArray(response)) {
  //           	response = JSON.parse(response);
  //           }
		// $('#collections').append(convertJsonToTableSecurityGroups(response));
		// });
    });
    


		
	});



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