@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<style>
    #AWSPricing_reserved_holder .chosen-container,#AWSPricing_ondemand_holder .chosen-container{max-width: 140px;}
</style>
<div class="container-fluid">
    <div class="clearfix contentBlock">
        <h4 class="display-block clearfix">AWS Pricing</h4>
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

@stop

{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/js/aws/awspricing.js')}}"></script>
<script src="{{asset('assets/js/xervmon/utils.js')}}"></script>
<script type="text/javascript">
	// @FIXME Perhaps a PHP based table generation solution is better suited
    window.ondemand_instance_prices = {{json_encode($ondemand)}};
</script>
@stop
