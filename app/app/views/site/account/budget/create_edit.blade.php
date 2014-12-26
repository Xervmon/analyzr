@extends('site.layouts.default')

@if(isset($budget->id)&& $mode=='edit')

@section('breadcrumbs', Breadcrumbs::render('EditBudget'))

@else

@section('breadcrumbs', Breadcrumbs::render('CreateBudget'))

@endif
{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('CreateBudget'))

@if(empty($accounts) || count($accounts) === 0)	

	<div class="alert alert-info">
		{{{ Lang::get('budget/budget.empty_budget_accounts') }}}
	</div>
	<a href="{{ URL::to('account/create') }}" class="btn btn-primary pull-right" role="button" id="acc_add_btn">{{{ Lang::get('account/account.add_account') }}}</a>	
   @else
	<div class="page-header">
		<div class="row">
			<div class="col-md-9">
				<h5>{{isset($budget->id)?'Edit':'Create'}} {{{ Lang::get('budget/budget.Budget') }}}</h5>
			</div>
		</div>
	</div>

	{{-- Create/Edit cloud budget Form --}}
	<form id="cloudProviderCredntialsForm" class="form-horizontal" method="post" action="@if (isset($budget->id)){{ URL::to('budget/' . $budget->id . '/edit') }}@endif" autocomplete="on">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->



		<!-- name -->

        <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="cloudAccountId">{{{ Lang::get('budget/budget.choose_an_account') }}} <font color="red">*</font></label>
			<div class="col-md-6">
				<select class="form-control" name="cloudAccountId" id="cloudAccountId">
					@foreach ($accounts as $key )
						<option value="{{$key->id}}" {{{ Input::old('cloudAccountId', isset($budget->cloudAccountId) && ($budget->cloudAccountId == $key->id) ? 'selected="selected"' : '') }}}>{{{ $key->name }}}</option>
					@endforeach
				</select>
			</div>
		</div></br>

		<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="budgetType">{{{ Lang::get('budget/budget.budgettype') }}}<font color="red">*</font></label>
			<div class="col-md-6">
				<select class="form-control" name="budgetType" id="budgetType" required>
					@foreach ($budgetType as $key )
						<option value="{{$key}}" {{{ Input::old('budgetType', isset($budget->budgetType) && ($budget->budgetType == $key) ? 'selected="selected"' : '') }}}>{{{ $key }}}</option>
					@endforeach
				</select>
			</div>
		</div></br>


        <div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="budget">{{{ Lang::get('budget/budget.setbudget') }}}<font color="red">*</font></label>
			<div class="col-md-6">
				<input class="form-control" type="text" name="budget" id="budget" value="{{{ Input::old('budget', isset($budget->budget) ? $budget->budget : null) }}}" required />
			</div>
		</div></br>


        <div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="budgetNotificationEmail">{{{ Lang::get('budget/budget.notification_email') }}}</label>
			<div class="col-md-6">
				<input class="form-control" type="email" name="budgetNotificationEmail" id="budgetNotificationEmail" value="{{{ Input::old('budgetNotificationEmail', isset($budget->budgetNotificationEmail) ? $budget->budgetNotificationEmail : null) }}}"  />
			</div>
		</div></br> 

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{ URL::to('budget') }}" class="btn btn-default" id="budget_back_btn">Back</a>
				<button type="submit" class="btn btn-primary" id="budget_save_btn">Save</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
	@endif
@stop

@section('scripts')
@stop
