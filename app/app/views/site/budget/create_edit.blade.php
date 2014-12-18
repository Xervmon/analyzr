@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('CreateBudget'))

	<div class="page-header">
		<div class="row">
			<div class="col-md-9">
				<h5>{{isset($budget->id)?'Edit':'Create'}} {{{ Lang::get('budget/budget.Budget') }}}</h5>
			</div>
		</div>
	</div>
<?php //echo '<pre>';print_r($accounts);die(); ?>
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
					@if($key->profileType == Constants::READONLY_PROFILE)
						<option value="{{$key->id}}" {{{ Input::old('cloudAccountId', isset($budget->cloudAccountId) && ($budget->cloudAccountId == $key->id) ? 'selected="selected"' : '') }}}>{{{ $key->name }}}</option>
						@endif
					@endforeach
				</select>
			</div>
		</div></br>

		<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="budget_type">{{{ Lang::get('budget/budget.budgettype') }}}<font color="red">*</font></label>
			<div class="col-md-6">
				<select class="form-control" name="budget_type" id="budget_type" required>
					@foreach ($budget_type as $key )
						<option value="{{$key}}" {{{ Input::old('budget_type', isset($ticket->budget_type) && ($ticket->budget_type == $key) ? 'selected="selected"' : '') }}}>{{{ $key }}}</option>
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


        <!-- <div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="notification_email">{{{ Lang::get('budget/budget.notification_email') }}}</label>
			<div class="col-md-6">
				<input class="form-control" type="email" name="notification_email" id="notification_email" value="{{{ Input::old('notification_email', isset($budget->notification_email) ? $budget->notification_email : null) }}}"  />
			</div>
		</div></br> -->

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{ URL::to('budget') }}" class="btn btn-default">Back</a>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop

@section('scripts')
@stop
