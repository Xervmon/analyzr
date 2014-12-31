@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('Scheduler'))

<div class="page-header">
    <div class="row">
        <div class="col-md-9">
            <h5>{{{ Lang::get('scheduler/scheduler.your_schedulers') }}}</h5>
        </div>
        <div class="col-md-3">
            <a href="{{ URL::to('scheduler/create') }}" class="btn btn-primary pull-right" role="button" id="budget_add_btn">{{{ Lang::get('scheduler/scheduler.add_scheduler') }}}</a>
        </div>
    </div>
</div>

<div class="media-block">
    <ul class="list-group">
        @if(!empty($schedulers))
        @foreach ($schedulers as $scheduler)

        <li class="list-group-item">
            <div class="media">
                <span class="pull-left" href="#"> <img class="media-object img-responsive"
                    src="{{ asset('/assets/img/providers/'.Config::get('provider_meta.'.$scheduler->cloudProvider.'.logo')) }}" alt="{{ $scheduler->cloudProvider }}" /> </span>

                <form class="pull-right" method="post" action="{{ URL::to('scheduler/' . $scheduler->id . '/delete') }}">
                    <!-- CSRF Token -->
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <!-- ./ csrf token -->
                    <button type="button" class="btn btn-warning pull-right" id="budget_delete_btn" role="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Scheduler" data-message="{{ Lang::get('scheduler/scheduler.scheduler_delete') }}">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>

                </form>
                <a href="{{ URL::to('scheduler/' . $scheduler->id . '/edit') }}" id="budget_edit_btn" class="btn btn-success pull-right" role="button"><span class="glyphicon glyphicon-edit"></span></a>

                <div class="media-body">
                    <h4 class="media-heading">{{ String::title($scheduler->name) }} : {{ String::title($scheduler->profileType) }}</h4><br>
                    
                    <p>
                        <b>Scheduler Starts On : </b> {{$scheduler->scheduler_starts_on}}
                     
                    </p>
                    <p>
                        <b>Update Scheduler  :</b> {{$scheduler->scheduler_update}} 
                     
                    </p>

                    <p>
                        <b>Created At : </b> <span class="glyphicon glyphicon-calendar"></span> {{{ $scheduler->created_at }}}

                    </p>
                </div>
            </div>
        </li>
        @endforeach
        @endif

    </ul>
    @if(empty($schedulers) || count($schedulers) === 0)
    <div class="alert alert-info">
        {{{ Lang::get('scheduler/scheduler.empty_schedulers') }}}
    </div>
    @endif
</div>
<div></div>
@include('deletemodal')


@stop
