@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('Budget'))

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('budget/budget.your_budgets') }}}</h5>
		</div>
		<div class="col-md-3">
			<a href="{{ URL::to('budget/create') }}" class="btn btn-primary pull-right" role="button">{{{ Lang::get('budget/budget.add_budget') }}}</a>
		</div>
	</div>
</div>

<div class="media-block">
	<ul class="list-group">
		@if(!empty($budgets))
		@foreach ($budgets as $budget)

		<li class="list-group-item">
			<div class="media">
				<span class="pull-left" href="#"> <img class="media-object img-responsive"
					src="{{ asset('/assets/img/providers/'.Config::get('provider_meta.'.$budget->cloudProvider.'.logo')) }}" alt="{{ $budget->cloudProvider }}" /> </span>

				<form class="pull-right" method="post" action="{{ URL::to('budget/' . $budget->id . '/delete') }}">
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<!-- ./ csrf token -->
					<button type="button" class="btn btn-warning pull-right" role="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Budget" data-message="{{ Lang::get('budget/budget.budget_delete') }}">
						<span class="glyphicon glyphicon-trash"></span>
					</button>

				</form>
				<a href="{{ URL::to('budget/' . $budget->id . '/edit') }}" class="btn btn-success pull-right" role="button"><span class="glyphicon glyphicon-edit"></span></a>

				<div class="media-body">
					<h4 class="media-heading">{{ String::title($budget->name) }} : {{ String::title($budget->profileType) }}</h4><br>
					
                    <p>
                     	<b>Budget Type :</b> {{$budget->budget_type}}
                     
                    </p>
                    <p>
                     	<b>Total Budget :</b> {{$budget->budget}} USD
                     
                    </p>

                    <p>
						<span class="glyphicon glyphicon-calendar"></span> {{{ $budget->created_at }}}

					</p>
				</div>
			</div>
		</li>
		@endforeach
		@endif

	</ul>
	@if(empty($budgets) || count($budgets) === 0)
	<div class="alert alert-info">
		{{{ Lang::get('budget/budget.empty_budgets') }}}
	</div>
	@endif
</div>
<div></div>
@include('deletemodal')


@stop
