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

<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="row">
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/ec2.png') }}}" />
						<div class="caption">
							<h5 style="text-align:center">
								{{{ 'Details Available:'.count($instanceDetails) }}}
							</h5>
							
							<p style="text-align:center">
								<a class="btn" href="{{ URL::to('account/' . $account->id . '/instanceInfo') }}">Details</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="thumbnail">
						<img alt="300x200" src="{{{ asset('assets/img/aws/ebs.png') }}}" />
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
						<img alt="300x200" src="{{{ asset('assets/img/aws/sg.png') }}}" />
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
						<img alt="300x200" src="{{{ asset('assets/img/aws/kp.png') }}}" />
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