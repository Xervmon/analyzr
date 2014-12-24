@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('AWS_Details',$account->id))

<script type="text/javascript">
function toggleChevron(e) {
    $(e.target)
        .prev('.panel-heading')
        .find("i.indicator")
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
}
$('#accordion').on('hidden.bs.collapse', toggleChevron);
$('#accordion').on('shown.bs.collapse', toggleChevron);
</script>

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('account/account.awsInstanceDetails') }}} : <a href="{{ URL::to('account/'.$account->id.'/edit') }}">{{ $account->name }} </a> </h5>
		</div>
	</div>
</div>

<div class="container">
	<div class="row clearfix">


<div class="panel-group" id="accordion">
	<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseEight">
        	<span class="glyphicon glyphicon-tags" style="margin-left: 26px; margin-right: 26px;"></span> 
        	{{{ Lang::get('account/account.aws_tags') }}}
		<i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
      </h4></a>
    </div>
    <div id="collapseEight" class="panel-collapse collapse">
      <div class="panel-body">
     

      					<?php 
      					 $tags_Env=0;$tags_Name=0;
      					foreach ($tags as $key_i => $value_i) { 

							if(!empty($value_i->Environment)) $tags_Env = count($value_i->Environment);
							if(!empty($value_i->Name)) $tags_Name = count($value_i->Name);
							if($tags_Env!=0 || $tags_Name!=0)
						{ ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
								<h5 class="media-heading">{{{ 'TAGS '. $key_i }}}</h5>
								<p style="text-align:center">
									<h4 class="media-heading">{{{ 'Details Available:' . ($tags_Env+$tags_Name) }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ ($tags_Env+$tags_Name) }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_tags') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/Tags') }}">Details</a>
											</p>
										</div>
									</li>
									<li class="list-group-item panel panel-status panel-success">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $tags_Env }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_Env') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/Tags') }}">Details</a>
											</p>
										</div>
									</li>
									<li class="list-group-item panel panel-status panel-danger">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $tags_Name }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_Name') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/Tags') }}">Details</a>
											</p>
										</div>
									</li>
								</ul>
							</div>
						

						</div>
						</div><?php } 
					    $tags_Env=0;$tags_Name=0;}?> 

      </div>
    </div>
  </div>


  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
         <img style="height: 33px; width: 73px;" alt="300x200" src="{{{ asset('assets/img/aws/ec2.png') }}}" />{{{ Lang::get('account/account.aws_ec2') }}}
      <i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
      </h4> </a>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">
					<?php foreach ($instances as $key_i => $value_i) { 
						$instances_sum = array_sum((array)$value_i); 
						if($instances_sum!=0)
						{ ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
								<h5 class="media-heading">{{{ 'EC2 '. $key_i }}}</h5>
								
									
									<p style="text-align:center">
								<h4 class="media-heading">{{{ 'Details Available:' . $instances_sum }}}</h4>
									
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $instances_sum }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_instances') }}}</strong>
											<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/'. $account->id.'/'.$key_i.'/EC2') }}">Details</a>
											</p>
										</div>
									</li>
									<?php if (!empty($value_i)) { 
										$i =0 ;
									foreach ($value_i as $key_is => $value_is) { ?>
									@if ($i == 0)
    									<li class="list-group-item panel panel-status panel-success">
									@elseif ($i == 1)
    									<li class="list-group-item panel panel-status panel-danger">								
									@elseif ($i == 2)
   										<li class="list-group-item panel panel-status panel-default">
									@else
										<li class="list-group-item panel panel-status panel-info">
									@endif
								
 									<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $value_is }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ ucfirst ( $key_is ) . ' Instances' }}}</strong>
											<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id.'/'.$key_i.'/EC2') }}">Details</a>
											</p>
										</div>
									</li>
									<?php $i++; } } ?>
								</ul>
							</div>
						

						</div>
						</div>
						<?php } 
					    } ?> 




      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
          <img style="height: 33px; width: 73px;" alt="300x200" src="{{{ asset('assets/img/aws/ebs.png') }}}" />{{{ Lang::get('account/account.aws_ebs') }}}
		<i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
      </h4></a>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">

						<?php foreach ($volumes as $key_i => $value_i) {
						$volumes_sum = array_sum((array)$value_i);
						if($volumes_sum!=0) {
						 ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
									<h5 class="media-heading">{{{ 'EBS '. $key_i }}}</h5>
									<p style="text-align:center">
									<h4 class="media-heading">{{{ 'Details Available:' . $volumes_sum }}}</h4>	
								
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $volumes_sum }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_volumes') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/EBS') }}">Details</a>
											</p>
										</div>
									</li>
									<?php if (!empty($value_i)) { 
										$i =0 ;
									foreach ($value_i as $key_is => $value_is) { ?>
									@if ($i == 0)
    									<li class="list-group-item panel panel-status panel-success">
									@elseif ($i == 1)
    									<li class="list-group-item panel panel-status panel-danger">								
									@elseif ($i == 2)
   										<li class="list-group-item panel panel-status panel-default">
									@else
										<li class="list-group-item panel panel-status panel-info">
									@endif
								
 									<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $value_is }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ ucfirst ( $key_is ) . ' Volumes' }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/EBS') }}">Details</a>
											</p>
										</div>
									</li>
									<?php $i++; } } ?>
								</ul>
							</div>
						

						</div>
						</div><?php } 
					    } ?> 
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
         <img style="height: 33px; width: 73px;" alt="300x200" src="{{{ asset('assets/img/aws/sec_group.png') }}}" />{{{ Lang::get('account/account.aws_sg') }}}
		 <i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
      </h4></a>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">

      					<?php foreach ($secgroups as $key_i => $value_i) { 
							$secgroups_sum = array_sum((array)$value_i);
							if($secgroups_sum!=0) {
						 ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
									<h5 class="media-heading">{{{ 'SG '. $key_i }}}</h5>
									<p style="text-align:center">
									<h4 class="media-heading">{{{ 'Details Available:' . $secgroups_sum }}}</h4>
								
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $secgroups_sum }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_secgroups') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/SecurityGroupsInfo') }}">Details</a>
											</p>
										</div>
									</li>
									<?php if (!empty($value_i)) { 
										$i =0 ;
									foreach ($value_i as $key_is => $value_is) { ?>
									@if ($i == 0)
    									<li class="list-group-item panel panel-status panel-success">
									@elseif ($i == 1)
    									<li class="list-group-item panel panel-status panel-danger">								
									@elseif ($i == 2)
   										<li class="list-group-item panel panel-status panel-default">
									@else
										<li class="list-group-item panel panel-status panel-info">
									@endif
								
 									<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $value_is }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ ucfirst ( $key_is ) . ' SecGroups' }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/SecurityGroupsInfo') }}">Details</a>
											</p>
										</div>
									</li>
									<?php $i++; } } ?>
								</ul>
							</div>
						

						</div>
						</div><?php } 
					    } ?> 



      </div>
    </div>
  </div>


    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
        	
      					<img alt="73x33" src="{{{ asset('assets/img/aws/key.png') }}}" />{{{ Lang::get('account/account.aws_kp') }}}
						
        <i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
      </h4></a>
    </div>
    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body">
						<?php foreach ($key_pairs as $key_i => $value_i) { 
						$key_pairs_sum = array_sum((array)$value_i); 
						if($key_pairs_sum!=0)
						{ ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
								
									<h5 class="media-heading">{{{ 'KP '. $key_i }}}</h5>
									<p style="text-align:center">
									<h4 class="media-heading">{{{ 'Details Available:' . $key_pairs_sum }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $key_pairs_sum }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_key_pairs') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/KeyPairs') }}">Details</a>
											</p>
										</div>
									</li>
									<?php if (!empty($value_i)) { 
										$i =0 ;
									foreach ($value_i as $key_is => $value_is) { ?>
									@if ($i == 0)
    									<li class="list-group-item panel panel-status panel-success">
									@elseif ($i == 1)
    									<li class="list-group-item panel panel-status panel-danger">								
									@elseif ($i == 2)
   										<li class="list-group-item panel panel-status panel-default">
									@else
										<li class="list-group-item panel panel-status panel-info">
									@endif
								
 									<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $value_is }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ ucfirst ( $key_is ) . ' Key Pairs' }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/KeyPairs') }}">Details</a>
											</p>
										</div>
									</li>
									<?php $i++; } } ?>
								</ul>
							</div>
						

						</div>
						</div>
						<?php } 
					    } ?> 



      </div>
    </div>
  </div>




      <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
        	<img style="height: 33px; width: 73px;" alt="300x200" src="{{{ asset('assets/img/aws/vpc.png') }}}" />{{{ Lang::get('account/account.aws_vpc') }}}
		<i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
      </h4></a>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse">
      <div class="panel-body">

      					<?php foreach ($vpc as $key_i => $value_i) { 
							$vpc_sum = array_sum((array)$value_i);
							if($vpc_sum!=0)
						{ ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
								<h5 class="media-heading">{{{ 'VPC '. $key_i }}}</h5>
								<p style="text-align:center">
									<h4 class="media-heading">{{{ 'Details Available:' . $vpc_sum }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $vpc_sum }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_vpc') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/VPC') }}">Details</a>
											</p>
										</div>
									</li>
									<?php if (!empty($value_i)) { 
										$i =0 ;
									foreach ($value_i as $key_is => $value_is) { ?>
									@if ($i == 0)
    									<li class="list-group-item panel panel-status panel-success">
									@elseif ($i == 1)
    									<li class="list-group-item panel panel-status panel-danger">								
									@elseif ($i == 2)
   										<li class="list-group-item panel panel-status panel-default">
									@else
										<li class="list-group-item panel panel-status panel-info">
									@endif
								
 									<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $value_is }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ ucfirst ( $key_is ) . ' VPC' }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/VPC') }}">Details</a>
											</p>
										</div>
									</li>
									<?php $i++; } } ?>
								</ul>
							</div>
						

						</div>
						</div><?php } 
					    } ?> 

      </div>
    </div>
  </div>


    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
        	<img style="height: 33px; width: 73px;" alt="300x200" src="{{{ asset('assets/img/aws/subnet.png') }}}" />{{{ Lang::get('account/account.aws_sn') }}}
         <i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
      </h4></a>
    </div>
    <div id="collapseSix" class="panel-collapse collapse">
      <div class="panel-body">

      						<?php foreach ($subnets as $key_i => $value_i) { 
							$subnets_sum = array_sum((array)$value_i);
							if($subnets_sum!=0)
						{ ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
								
								<h5 class="media-heading">{{{ 'SN '. $key_i }}}</h5>
									<p style="text-align:center">									
									<h4 class="media-heading">{{{ 'Details Available:' . $subnets_sum }}}</h4>
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $subnets_sum }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_subnets') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/Subnets') }}">Details</a>
											</p>
										</div>
									</li>
									<?php if (!empty($value_i)) { 
										$i =0 ;
									foreach ($value_i as $key_is => $value_is) { ?>
									@if ($i == 0)
    									<li class="list-group-item panel panel-status panel-success">
									@elseif ($i == 1)
    									<li class="list-group-item panel panel-status panel-danger">								
									@elseif ($i == 2)
   										<li class="list-group-item panel panel-status panel-default">
									@else
										<li class="list-group-item panel panel-status panel-info">
									@endif
								
 									<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $value_is }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ ucfirst ( $key_is ) . ' Subnets' }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/Subnets') }}">Details</a>
											</p>
										</div>
									</li>
									<?php $i++; } } ?>
								</ul>
							</div>
						

						</div>
						</div><?php } 
					    } ?> 


      </div>
    </div>
  </div>

      <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
        <img style="height: 33px; width: 73px;" alt="300x200" src="{{{ asset('assets/img/aws/rds.png') }}}" />{{{ Lang::get('account/account.aws_rds') }}}
						
        <i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
      </h4></a>
    </div>
    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body">

      					<?php foreach ($rds as $key_i => $value_i) { 
							$rds_sum = array_sum((array)$value_i);
							if($rds_sum!=0)
						{ ?>
						<div class="bs-callout bs-callout-default">

						<div class="media">
							
							<div class="media-left pull-left text-center" href="#">
								<h5 class="media-heading">{{{ 'RDS '. $key_i }}}</h5>
								<p style="text-align:center">
								<h4 class="media-heading">{{{ 'Details Available:' . $rds_sum }}}</h4>	
							</div>
							<div class="media-body bs-callout-danger">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item panel panel-status panel-primary">
										<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $rds_sum }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ Lang::get('account/account.total_rds') }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/EC2') }}">Details</a>
											</p>
										</div>
									</li>
									<?php if (!empty($value_i)) { 
										$i =0 ;
									foreach ($value_i as $key_is => $value_is) { ?>
									@if ($i == 0)
    									<li class="list-group-item panel panel-status panel-success">
									@elseif ($i == 1)
    									<li class="list-group-item panel panel-status panel-danger">								
									@elseif ($i == 2)
   										<li class="list-group-item panel panel-status panel-default">
									@else
										<li class="list-group-item panel panel-status panel-info">
									@endif
								
 									<div class="panel-heading">
											<h1 class="panel-title text-center">{{{ $value_is }}}</h1>
										</div>
										<div class="panel-body text-center">
											<strong>{{{ ucfirst ( $key_is ) . ' RDS' }}}</strong>
										<p style="text-align:center">
												<a class="btn" href="{{ URL::to('assets/' . $account->id . '/EC2') }}">Details</a>
											</p>
										</div>
									</li>
									<?php $i++; } } ?>
								</ul>
							</div>
						

						</div>
						</div><?php } 
					    } ?> 



      </div>
    </div>
  </div>




</div>

		</div>	
	</div>

						
@stop

@stop