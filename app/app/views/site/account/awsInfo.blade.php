@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{ $account->name }} : {{{ Lang::get('account/account.awsDetails') }}}</h5>
		</div>
	</div>
</div>

<!-- <div id="securityGroups">
</div> -->
@for ($i = 0; $i < count($instanceDetails['Reservations']); $i++)
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
						<h3><!--<img alt="300x200" src="{{{ asset('assets/img/aws/ec2.png') }}}" />-->{{{ Lang::get('account/account.aws_ec2') }}}</h3>
						<div class="bs-callout bs-callout-default">
						<div class="media">
							<div class="media-left pull-left text-center" href="#">
								<h4 class="media-heading">{{{ 'Details Available:'.count($instanceDetails) }}}</h4>
									<p style="text-align:center">
										<a class="btn" href="{{ URL::to('account/' . $account->id . '/instanceInfo') }}">Details</a>
									</p>
									<p style="text-align:center">
									<h4 class="media-heading">{{{ 'EC2 '. $instanceDetails['Reservations'][$i]['Instances'][0]['Placement']['AvailabilityZone'] }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-success">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ count($instanceDetails['Reservations'][$i]['Instances']) }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_instances') }}}</strong>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
						<h3><!--<img alt="300x200" src="{{{ asset('assets/img/aws/ebs.png') }}}" />-->{{{ Lang::get('account/account.aws_ebs') }}}</h3>
						<div class="bs-callout bs-callout-default">
						<div class="media">
							<div class="media-left pull-left text-center" href="#">
								<h4 class="media-heading">{{{ 'Details Available:'.count($instanceDetails) }}}</h4>
									<p style="text-align:center">
										<a class="btn" href="{{ URL::to('account/' . $account->id . '/instanceInfo') }}">Details</a>
									</p>
									<p style="text-align:center">
									<h4 class="media-heading">{{{ 'EBS '. $instanceDetails['Reservations'][0]['Instances'][0]['Placement']['AvailabilityZone'] }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
								</ul>
							</div>
						</div>
					</div>
						<h3><!--<img alt="300x200" src="{{{ asset('assets/img/aws/sg.png') }}}" />-->{{{ Lang::get('account/account.aws_sg') }}}</h3>
						<div class="bs-callout bs-callout-default">
						<div class="media">
							<div class="media-left pull-left text-center" href="#">
								<h4 class="media-heading">{{{ 'Details Available:'.count($instanceDetails) }}}</h4>
									<p style="text-align:center">
										<a class="btn" href="{{ URL::to('account/' . $account->id . '/instanceInfo') }}">Details</a>
									</p>
									<p style="text-align:center">
									<h4 class="media-heading">{{{ 'Security Group '. $instanceDetails['Reservations'][0]['Instances'][0]['Placement']['AvailabilityZone'] }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									
								</ul>
							</div>
						</div>
					</div>
						<h3><!--<img alt="300x200" src="{{{ asset('assets/img/aws/kp.png') }}}" />-->{{{ Lang::get('account/account.aws_kp') }}}</h3>
						<div class="bs-callout bs-callout-default">
						<div class="media">
							<div class="media-left pull-left text-center" href="#">
								<h4 class="media-heading">{{{ 'Details Available:'.count($instanceDetails) }}}</h4>
									<p style="text-align:center">
										<a class="btn" href="{{ URL::to('account/' . $account->id . '/instanceInfo') }}">Details</a>
									</p>
									<p style="text-align:center">
									<h4 class="media-heading">{{{ 'Key Pair '. $instanceDetails['Reservations'][0]['Instances'][0]['Placement']['AvailabilityZone'] }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									
								</ul>
							</div>
						</div>
					</div>
		</div>

			<div class="col-md-12 column">
			<div class="row">
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/eip.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/ri.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/elb.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
							  <a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
					<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/images.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								 <a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

				<div class="col-md-12 column">
			<div class="row">
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/rds.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/s3.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/vpc.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
					<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/as.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>	


						<div class="col-md-12 column">
			<div class="row">
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/lc.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								No details available..!
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="#">Details</a>
							</p>
						</div>
					</div>
				</div>
													
			</div>
		</div>	
	</div>
	</div>
@endfor
@stop


{{-- Scripts --}}
@section('scripts')
    <script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$.ajax({
			url:  "{{ URL::to('account/'.$account->id.'/SecurityGroupsData') }}",
			cache: false
		})
		.done(function( response ) {
			console.log(response);
			if (!$.isArray(response)) {
            	response = JSON.parse(response);
            }
		$('#securityGroups').append(convertJsonToTableSecurityGroups(response));
		});
	});
	</script>
@stop