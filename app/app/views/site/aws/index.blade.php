@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('aws/aws.your_aws_pricing') }}}</h5>
		</div>
	</div>
</div>

<?php
?>

@stop


<style>
    #AWSPricing_reserved_holder .chosen-container,#AWSPricing_ondemand_holder .chosen-container{max-width: 140px;}
</style>
<div class="container-fluid">
    <div class="clearfix contentBlock">
        <h4 class="display-block clearfix">AWS Pricing</h4>
        <div  class="contentBlockNew well-small">
            <legend>
                Reserved Instance Pricing:
            </legend>
            <div id="AWSPricing_reserved_holder">
                <div class="text-center well well-small">
                    <i class="icon-spinner icon-spin"></i> Loading ...
                </div>
            </div>
        </div>
        <div  class="contentBlockNew well-small">
            <legend>
                On-Demand Instance Pricing:
            </legend>
            <div id="AWSPricing_ondemand_holder">
                <div class="text-center well well-small">
                    <i class="icon-spinner icon-spin"></i> Loading ...
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/aws/awspricing.js')}}"></script>
<script type="text/javascript">
	// @FIXME Perhaps a PHP based table generation solution is better suited
    window.reserved_instance_prices= '<?=json_encode($ec2Data['reserved_instances'])?>';
    window.ondemand_instance_prices='<?=json_encode($ec2Data['ondemand'])?>';
</script>