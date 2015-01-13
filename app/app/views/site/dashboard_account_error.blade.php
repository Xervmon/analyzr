@foreach($chartDataForAccounts['series'] as $row => $value)

	@if($value['total'] == 0)
	 @if(!isset($value['discount']))
		<div class="col-md12">
			<div class="alert alert-danger alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				
				<h4>Error</h4>

   				{{ UIHelper::getAccountAnchor($row) }}  {{{ Lang::get('account/account.check_accountId') }}} or {{ Lang::get('account/account.create_ticket') }}
   				
			</div>
		</div>
	 @else
   			<div class="col-md12">
			<div class="alert alert-success alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				
				<h4>Success</h4>

   				{{ UIHelper::getAccountAnchor($row) }}  {{{ Lang::get('account/account.discount_added').' '.$value['discount'] }}}
   				
			</div>
		</div>
   	 @endif

	@endif 
@endforeach
