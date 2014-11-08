@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
{{{ Lang::get('site.roadmap') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
<h4>{{{ Lang::get('site.roadmap') }}}</h4>
<div class="row">
  	<div class="col-md-12">
  		<h4>What to expect?</h4>
  		<p>
		While our roadmap items will be defined by our service users, the following are a gist of items that we would cover in next few releses.
		<ul>
			<li>Collaborate and Integrate with <a href="https://www.aws.amazon.com">Amazon AWS</a> eco system</li>
			<li>Security Port and Vulnerability Scans</li>
			<li>MSP specific requirements</li>
			<li>Billing and Chargebacks</li>
			<li>More coming soon!</li>
			
		</ul>
		</p>
		<div class="text-center">
	       	 <!-- You can move inline styles to css file or css block. -->
	       	 <p><h4><font color="3399FF">Compliance : Gain insights and customize as per your organization needs.</font></h4></p>
		    <div id="slider1_container" style="position: relative; top: 0px; center: 0px; width: 980px; height: 100px; overflow: hidden; ">
		
		        <!-- Loading Screen -->
		        <div u="loading" style="position: absolute; top: 0px; center: 0px;">
		            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
		                background-color: #000; top: 0px; left: 0px;width: 100%;height:100%;">
		            </div>
		            <div style="position: absolute; display: block; background: url({{{ asset('assets/img/loading.gif') }}}) no-repeat center center;
		                top: 0px; left: 0px;width: 100%;height:100%;">
		            </div>
		        </div>
		
		        <!-- Slides Container -->
		        <div u="slides" style="cursor: move; position: absolute; center: 0px; top: 0px; width: 980px; height: 100px; overflow: hidden;">
		            <div><img u="image" title="Amazon AWS" alt="Amazon AWS " src="{{ asset('/assets/img/providers/'.Config::get('provider_meta.Amazon AWS'.'.logo')) }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/fedramp-logo.jpg') }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/fips-logo.jpg') }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/hipaa-logo.png') }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/iso-logo.jpg') }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/itar-logo.jpg') }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/pci-logo.jpg') }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/soc2-logo.jpg') }}" /></div>
		            <div><img u="image" title="Compliance : Gain insights and customize as per your organization needs." alt="Compliance : Gain insights and customize as per your organization needs." src="{{ asset('/assets/img/providers/SOCLogoSOs.jpg') }}" /></div>    	
		           
		        </div>
	     	</div>
       </div>
		<p>Call us today on (800) 813-1315 or <a href="mailto:roapmap-awsusageanalyzr@xervmon.com">email us </a> with your feature request in relation to Analyzr service. </p>
		
	</div>
</div>
@stop






