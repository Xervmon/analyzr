@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
{{{ Lang::get('site.cloudExperts') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('CloudExperts'))
<h4>{{{ Lang::get('site.cloudExperts') }}}</h4>
<div class="row">
	<div class="col-md-12">
  		<h4>Support</h4>
  		<p>
		 Customers have the choice of using the popular open source Cost management tool such as Netflix Ice or flexible and MSP friendly
		 Analyzr. We offer flexible packages to support 99.9999 availability of your cloud foundation solutions.
		 We leverage <a href="https://www.xervmon.com">Xervmon Platform</a> to support and monitor the services, there by delivering
		 better quality of service at extremely affordable costs.
		</p>
		<p>
			Our team can also customize billing solutions for your needs, implement custom billing workflows, manage, audit and monitor them.
		</p>
		<p>Call us today on (800) 813-1315 or <a href="mailto:xdockersupport@xervmon.com">email us </a> with your questions in relation to our support packages. </p>
		
	</div>
 </div>
 
@stop






