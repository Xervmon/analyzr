@foreach($chartDataForAccounts['series'] as $row => $value)
	@if($value == 0)
		<div class="col-md12">
			<div class="alert alert-danger alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<h4>Error</h4>
   				{{ UIHelper::getAccountAnchor($row) }}  {{{ Lang::get('account/account.check_accountId') }}}
			</div>
		</div>
	@endif	
@endforeach