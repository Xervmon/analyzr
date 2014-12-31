@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('CreateScheduler'))

@if(empty($accounts) || count($accounts) === 0) 

    <div class="alert alert-info">
        {{{ Lang::get('scheduler/scheduler.empty_scheduler_accounts') }}}
    </div>
    <a href="{{ URL::to('account/create') }}" class="btn btn-primary pull-right" role="button" id="acc_add_btn">{{{ Lang::get('account/account.add_account') }}}</a>    
   @else
    <div class="page-header">
        <div class="row">
            <div class="col-md-9">
                <h5>{{isset($scheduler->id)?'Edit':'Create'}} {{{ Lang::get('scheduler/scheduler.Scheduler') }}}</h5>
            </div>
        </div>
    </div>
    
    <div class='col-sm-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker3'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
    </div>
    @endif
@stop
{{-- Styles --}}
@section('styles')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">

{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/js/bootstrap/moment.js')}}"></script>
<!-- <script src="{{asset('assets/js/bootstrap/transition.js')}}"></script>
<script src="{{asset('assets/js/bootstrap/collapse.js')}}"></script>-->
<script src="{{asset('assets/js/bootstrap/bootstrap-datetimepicker.js')}}"></script> 
<script src="{{asset('assets/js/xervmon/utils.js')}}"></script>


<script type="text/javascript">
            $(function () {
                 $('#datetimepicker3').datetimepicker({
                    pick12HourFormat: false,
                    minDate: moment()
                });
            });
        </script>
@stop
