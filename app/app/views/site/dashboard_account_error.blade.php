@foreach($chartDataForAccounts['series'] as $row => $value)
	@if($value == 0)
		<div class="col-md12">
			<div class="alert alert-danger alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<h4>Error</h4>
   				<?php echo $row . ' shows 0 total. Please double check the Account Id and credentials for the account ';?>
			</div>
		</div>
	@endif	
@endforeach