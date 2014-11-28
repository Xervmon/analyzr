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
			<li>Security Audits (This is currently under heavy testing)
				<p>
					It's important to keep track of changes in your infrastructure's security settings. 
					One way to do this is to first set up a security auditer role (JSON template), which will give anyone assigned that role read-only access 
					to any security related settings on your account. 
					
					We go over all the items in your account and produce a canonical output showing your configuration. 
					Compare its output to the output from the previous run. Any differences will show you exactly what has been changed in your 
					security configuration. 
					It's useful to set this up and just have it email you the diff of any changes. (Source: Intrusion Detection in the Cloud - <a href="https://www.youtube.com/user/AmazonWebServices/Cloud?x=us-en_reinvent_1878_35">Video</a> & <a href="http://awsmedia.s3.amazonaws.com/SEC402.pdf">Presentation</a>)
					
				</p>	
			</li>
			<li>MSP specific requirements</li>
			<li>Billing and Chargebacks</li>
			<li>More coming soon!</li>
			
		</ul>
		</p>
		 <script src="{{asset('assets/js/jquery-plugins/prettify.js')}}"></script>
         
		<p>Call us today on (800) 813-1315 or <a href="mailto:roapmap-awsusageanalyzr@xervmon.com">email us </a> with your feature request in relation to Analyzr service. </p>
		
	</div>
</div>
@stop






