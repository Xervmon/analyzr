@extends('site.layouts.default')

{{-- Content --}}
@section('content')

@if(isset($scheduler->id)&& $mode=='edit')

@section('breadcrumbs', Breadcrumbs::render('EditScheduler'))

@else

@section('breadcrumbs', Breadcrumbs::render('CreateScheduler'))

@endif

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

    {{-- Create/Edit cloud budget Form --}}


<form id="cloudProviderCredntialsForm" class="form-horizontal" method="post" action="@if (isset($budget->id)){{ URL::to('scheduler/' . $scheduler->id . '/edit') }}@endif" autocomplete="on">

        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <!-- ./ csrf token -->


        <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="cloudAccountId">{{{ Lang::get('scheduler/scheduler.choose_an_account') }}}<font color="red">*</font></label>
            <div class="col-md-6">
                <select class="form-control" name="cloudAccountId" id="cloudAccountId" required>
                        <option>{{{ Lang::get('scheduler/scheduler.select_an_account') }}}</option>
                   @foreach ($accounts as $value)
                        <option value="{{ $value->id}}"{{{ Input::old('cloudAccountId', isset($scheduler->cloudAccountId) && ($scheduler->cloudAccountId == $value->id) ? 'selected="selected"' : '') }}}>{{ $value->name}}</option>
                    @endforeach
                </select>
            </div>
        </div></br> 


        <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="service">{{{ Lang::get('scheduler/scheduler.choose_an_service') }}}<font color="red">*</font></label>
            <div class="col-md-6">
                <select class="form-control" name="service" id="service" required>
                        <option value="" >{{{Lang::get('scheduler/scheduler.select_an_service')}}}</option>
                        <option value="{{ 'EC2'}}"{{{ Input::old('service', isset($scheduler->service) && ($scheduler->service == 'EC2') ? 'selected="selected"' : '') }}} >{{ 'EC2'}}</option>
                </select>
            </div>
        </div></br>
         

        <div class="form-group  {{{ $errors->has('email') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="region">{{{Lang::get('scheduler/scheduler.choose_an_region')}}}<font color="red">*</font></label>
            <div class="col-md-6">
                <select class="form-control" name="region" id="region" required>
                        <option value="" >{{{Lang::get('scheduler/scheduler.select_an_region')}}}</option>
                        @foreach ($regions as $region)
                        <option value="{{$region}}" {{{ Input::old('region', isset($scheduler->region) && ($scheduler->region == $region) ? 'selected="selected"' : '') }}}  >{{$region}}</option>
                        @endforeach 
                </select> 
            </div> 
        </div></br>                               
                                        
           
       
        <div id="instance"></div> 

        @if(!empty($scheduler->instance))
        <div id="editinstance"></div>    
        @endif


        <div class="row clearfix" style="margin-top:5%">

	         <div class="col-md-12 column">
			
			       <div class="jumbotron">

				        <h4>
					        {{{ Lang::get('scheduler/scheduler.setScheduler') }}}
				        </h4></br>


        
        <div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="scheduler_starts_on">{{{ Lang::get('scheduler/scheduler.scheduler_start_on') }}}</label>
            <div class="col-md-6">
                <div class='input-group date timepicker' id='datetimepicker3'>
                    <input type='text' class="form-control" id="scheduler_starts_on" name="scheduler_starts_on" value="{{{ Input::old('scheduler_starts_on', isset($scheduler->scheduler_starts_on) ? $scheduler->scheduler_starts_on : null) }}}" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div></br>


        <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="scheduler_update">{{{ Lang::get('scheduler/scheduler.update_scheduler') }}}<font color="red">*</font></label>
            <div class="col-md-6">
                <select class="form-control" name="scheduler_update" id="scheduler_update" required>
                        <option>{{{ Lang::get('scheduler/scheduler.select_an_frequency') }}}</option>
                   @foreach ($Updatescheduler as $value )
                        <option value="{{$value}}"{{{ Input::old('scheduler_update', isset($scheduler->scheduler_update) && ($scheduler->scheduler_update == $value) ? 'selected="selected"' : '') }}} >{{{ $value }}}</option>
                    @endforeach
                </select>
            </div>
        </div></br>
        

        <div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="scheduler_status">{{{ Lang::get('scheduler/scheduler.set_scheduler') }}}</label>
            <div class="col-md-1">
           
                <input class="form-control" type="checkbox" name="scheduler_status" id="scheduler_status" value="Yes"  <?php if(isset($scheduler->scheduler_status)) echo 'checked="checked"';?>  />
            </div>
        </div></br> 

        <div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="schedulerNotificationEmail">{{{ Lang::get('scheduler/scheduler.notification_email') }}}</label>
            <div class="col-md-6">
                <input class="form-control" type="email" name="schedulerNotificationEmail" id="schedulerNotificationEmail" placeholder="Enter email" value="{{{ Input::old('schedulerNotificationEmail', isset($scheduler->schedulerNotificationEmail) ? $scheduler->schedulerNotificationEmail : null) }}}" />
            </div>
        </div>
         

     <!--    <div class="form-group"> 
            <div class="radio">
                <label><input type="radio" name="optradio">Untill I change or Cancel this Scheduler</label>
            </div>

            <div class="radio">
                <label><input type="radio" name="optradio">Untill the Total number <input class="form-control" placeholder="Enter ..." type="text"> of Scheduler is executed</label>
            </div>

           <div class="radio">
               <label><input type="radio" name="optradio">Untill but not after
                   <div class='input-group date' id='datetimepicker31'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
               </label>
            </div>  
        </div>  -->


        </div>

            </div>

               </div>
	 
        <!-- Form Actions -->
        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <a href="{{ URL::to('scheduler') }}" class="btn btn-default" id="scheduler_back_btn">Back</a>
                <button type="submit" class="btn btn-primary" id="scheduler_save_btn">Save</button>
            </div>
        </div>
        <!-- ./ form actions -->

          
  </form>

@endif
@stop
{{-- Styles --}}
@section('styles')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">


{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/js/bootstrap/moment.js')}}"></script>
<script src="{{asset('assets/js/bootstrap/bootstrap-datetimepicker.js')}}"></script> 
<script src="{{asset('assets/js/xervmon/utils.js')}}"></script>


<script>

     var scheduler = '<?=json_encode($scheduler)?>';

      $(function () {
        
              if (scheduler !== "") 
              {
             scheduler = JSON.parse(scheduler);
             editinstance(scheduler);
               }

              function editinstance(scheduler){

                 $('#editinstance').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 400px;" height="50px" width="70px">');

                  var accountId = scheduler.cloudAccountId;
                  var region    = scheduler.region;

                  var url = "{{ URL::to('scheduler/:id/:region/instances') }}";
                  url = url.replace(':id', accountId );
                  url = url.replace(':region',region);
                  $.ajax({
                    url:url,
                    cache: false
                        })
                      .done(function( response ) { 
                                result ='<div class="form-group">';
                             if(response !== '[]' && response.length > 0){
                                response = JSON.parse(response);
                                result+='<label class="col-md-2 control-label" for="instance">{{Lang::get('scheduler/scheduler.choose_an_instance')}}<font color="red">*</font></label>';
                                result+='<div class="col-md-6">';
                                result+='<select class="form-control" name="instance" id="instance" required>';
                                result+='<option value="" >{{Lang::get('scheduler/scheduler.select_an_instance')}}</option>';
                                result+='<ul>';
                                var i ;
                                for(var i = 0; i < response.length; i++){
                              
                                result+= '<option value="'+response[i].InstanceId+' '+' - '+' '+ response[i].KeyName+'" '+(scheduler.instance === response[i].InstanceId+' '+' - '+' '+ response[i].KeyName ? 'selected="selected"' : '')+'  >'+response[i].InstanceId+' '+' - '+' '+ response[i].KeyName+'</option>'; 

                                }
                                result+= '</select>';                              
                                result+= '</div>'; 
                              }else{
                                result+= '<b style="margin-left:30em";>No Instances Found<b>';   
                              }                            
                                result+= '</div>';

                              $('#editinstance').html(result);

                             });
                 
                    }


              $('#datetimepicker3').datetimepicker({
                  format : 'YYYY/MM/DD HH:mm:00',
                  minDate: moment()
                });
                
               

              $('#region').change(function(){

                 $('#instance').html('<img src="{{asset('assets/img/ajax-loader.gif')}}" id="ajaxSpinnerImage" title="working..." style="margin-top:20px; margin-left: 400px;" height="60px" width="70px">');

                   var accountId = $('#cloudAccountId').val();
                   var region    = $('#region').val();

                   var url = "{{ URL::to('scheduler/:id/:region/instances') }}";
                   url = url.replace(':id', accountId );
                   url = url.replace(':region',region);
                   $.ajax({
                    url:url,
                    cache: false
                        })
                      .done(function( response ) { 
                             
                                result ='<div class="form-group">';
                             if(response !== '[]' && response.length > 0){
                                response = JSON.parse(response);
                                result+='<label class="col-md-2 control-label" for="instance">{{Lang::get('scheduler/scheduler.choose_an_instance')}}<font color="red">*</font></label>';
                                result+='<div class="col-md-6">';
                                result+='<select class="form-control" name="instance" id="instance" required>';
                                result+='<option value="" >{{Lang::get('scheduler/scheduler.select_an_instance')}}</option>';
                                result+='<ul>';
                                var i ;
                                for(var i = 0; i < response.length; i++){
                              
                                result+= '<option value="'+response[i].InstanceId+' '+' - '+' '+ response[i].KeyName+'" >'+response[i].InstanceId+' '+' - '+' '+ response[i].KeyName+'</option>';  
                                }
                                result+= '</select>';                              
                                result+= '</div>'; 
                                }else{
                                result+= '<b style="margin-left:30em";>No Instances Found<b>';   
                                }                            
                                result+= '</div>';

                              $('#instance').html(result);
                            
                             });

                       });   

            });

</script>
@stop



