@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-md-9">
			<h5>{{{ Lang::get('account/account.your_accounts') }}}</h5>
		</div>
	</div>
</div>

<table id="securityGroups" class="table table-striped table-hover">
		<thead>
			<tr>
				<th class="col-md-4">{{{ Lang::get('accounts/table.GroupName') }}}</th>
				<th class="col-md-4">{{{ Lang::get('accounts/table.GroupId') }}}</th>
				<th class="col-md-4">{{{ Lang::get('accounts/table.Description') }}}</th>
				<th class="col-md-4">{{{ Lang::get('accounts/table.Misc') }}}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

@stop


{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
			oTable = $('#securityGroups').dataTable( {
				"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('accounts/SecurityGroups') }}",
		        "fnDrawCallback": function ( oSettings ) {
	           		$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
	     		}
			});
		});
	</script>
@stop