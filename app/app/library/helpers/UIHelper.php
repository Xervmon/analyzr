<?php

/**
 * Class and Function List:
 * Function list:
 * - getDeploymentStatus()
 * Classes list:
 * - UIHelper
 */
class UIHelper
{
	public static function getLabel($status)
	{
		
		/*
		 * Default	<span class="label">Default</span>
Success	<span class="label label-success">Success</span>
Warning	<span class="label label-warning">Warning</span>
Important	<span class="label label-important">Important</span>
Info	<span class="label label-info">Info</span>
Inverse	<span class="label label-inverse">Inverse</span>
		 */
		switch($status)
		{
			case 'running' :
			case 'OK' :	
			case 'available' :	
			case Lang::get('account/account.STATUS_COMPLETED') 	: return '<span class="label label-success">'.$status.'</span>'; break;
				
			case Lang::get('account/account.STATUS_IN_PROCESS')  : 
			case 'start' 		: 
			case 'started' 		: return '<span class="label label-info">'.$status.'</span>'; break;
			case 'stop' 		: return '<span class="label label-warning">'.$status.'</span>'; break;
			case 'error' 		:
			case 'in-use' :		
			case 'failed' 		: return '<span class="label label-danger">'.$status.'</span>'; break;
			default:			  return '<span class="label label-danger">'.$status.'</span>'; break;
								
		}
		
	}
	
	public static function getTicketToggle($state)
	{
		switch($state)
		{
			case 1: return '<span class="label label-success">Active</span>'; break;
			case 0: return '<span class="label label-info">Closed</span>'; break;
		}
		
	}
	public static function getBadge($status)
	{
		
		/*
		 * Default	<span class="label">Default</span>
Success	<span class="label label-success">Success</span>
Warning	<span class="label label-warning">Warning</span>
Important	<span class="label label-important">Important</span>
Info	<span class="label label-info">Info</span>
Inverse	<span class="label label-inverse">Inverse</span>
		 */
		switch($status)
		{
			case 'running' 	: return '<span class="badge alert-success">'.$status.'</span>'; break;
			
			case 'start' 		: 
			case 'started' 		: return '<span class="badge alert-info">'.$status.'</span>'; break;
			
			case 'stop' 		: 
			case 'stopped' 		: return '<span class="badge alert-warning">'.$status.'</span>'; break;
			
			case 'terminated' 		: 
			case 'error' 		: 
			case 'failed' 		: return '<span class="badge alert-danger">'.$status.'</span>'; break;
			
			default:			  return '<span class="badge alert-danger">'.$status.'</span>'; break;
								
		}
		
	}

	public static function getLabel2($status, $val)
	{
		switch($status)
		{
			case true :
			case 'OK' 	: return '<span title="Status"><span class="label label-success">'.$val.'</span></span>'; break;
			
			case 'info' 		: return '<span title="Status"><span class="label label-info">'.$val.'</span></span>'; break;
			
			case 'default' 		: return '<span title="Status"><span class="label label-warning">'.$val.'</span></span>'; break;
			
			case false:
			case 'danger' 		: return '<span title="Status"><span class="label label-danger">'.$val.'</span></span>'; break;
			
			default:			  return '<span title="Status"><span class="label label-danger">'.$val.'</span></span>'; break;
								
		}
	}

	public static function getDataOrganized($params)
	{
			//Array ( [config] => Array ( [currency] => USD [unit] => perhr ) [regions] => 
			//Array ( [0] => Array ( [region] => us-east-1 [instanceTypes] 
			//=> Array ( [0] => Array ( [type] => m1.small [os] => linux [price] => 0.06 ) ) ) ) )
			$data = EC2InstancePrices::OnDemand($params);
			$input = json_decode($params);
			$instanceType = $data['regions'][0]['instanceTypes'];
			$obj = json_decode(json_encode($data));
			
			$image = self::getImage($input->instanceAmi);
			
			return '<h5> Cost Details</h5><div class="table-responsive">  <table class="table table-bordered"> '.
              		' <thead> ' .
                		'<th>Ondemand</th>'.
	                    	'<th>Region</th> '.
	                    	'<th>Instance Type</th>'.
	                    	'<th>OS Flavor</th>'.
	                    	'<th>Price</th>'.
                	'</thead>'.
              		'<tr>'.
	              		'<td align="center"><span class="glyphicon glyphicon-ok"></span></td>'.
	              		'<td><span class="glyphicon glyphicon-flag"></span>'.$obj->regions[0]->region.'</td>'.
	              		'<td>'.$input->instanceType.'</td>'.
	              		'<td>'.$image.'</td>'.
	              		'<td>'.self::getPrice($data).'</td>'.
					'</tr>'.
					'</table></div>';
			
	}	

	private static function getImage($instanceAMI)
	{
		$images = Config::get('images');
		foreach($images as $providers)
		{
			foreach($providers as $confImages)
			{
				foreach($confImages as $cImage)
				{
					$arr = explode (':', $cImage);
					if(!empty($arr[1]) && $arr[1] == $instanceAMI)
						return $cImage;
					else
					{
						return $instanceAMI;
					}
				}
			}
		}
	}
	
	private static function getPrice($data)
	{
		$sym = '';
		switch ($data['config']['currency'])
		{
			case 'USD' : $sym = '$ '; break;
		}
		
		if(!empty($data['regions'][0]['instanceTypes'][0]['price']))
		{
			$price = $data['regions'][0]['instanceTypes'][0]['price'];
			$perHr = $sym . $price.' '. $data['config']['unit'];
			$s30hr = $sym .floatval($price) * 730 . '<br/>'. Lang::get('deployment/deployment.monthly');;
			return $perHr .'<br/>'. $s30hr;
		}
		else return 'NA';
	}
	
	public static function getContainer($data)
	{
		/*
		 * Array ( [0] => stdClass Object ( [Command] => /bin/sh -c /home/ice/netflix-ice.sh 
		 * [Created] => 1413937646 [Id] => 4f262ebac076ee717539e082918d97393111d1b4de24415b6a30cbfbf74aa502 
		 * [Image] => xdocker/netflix_ice:v2 [Names] => Array ( [0] => /desperate_turing ) 
		 * [Ports] => Array ( [0] => stdClass Object ( [IP] => 0.0.0.0 [PrivatePort] => 443 [PublicPort] => 443 [Type] => tcp ) 
		 * [1] => stdClass Object ( [IP] => 0.0.0.0 [PrivatePort] => 5000 [PublicPort] => 5000 [Type] => tcp ) 
		 * [2] => stdClass Object ( [IP] => 0.0.0.0 [PrivatePort] => 8080 [PublicPort] => 8080 [Type] => tcp ) ) 
		 * [Status] => Up 3 hours ) )
		 * 
		 */
		
		 if(!empty($data))
		 {
		 	 $str = '<h5> Container Details</h5><div class="table-responsive">  <table class="table table-bordered"> '.
              		' <thead> ' .
                		'<th>Command</th>'.
	                    	'<th>Image</th>'.
	                    	'<th>Ports</th>'.
	                    	'<th>Created</th> '.
                	'</thead>';
		 	foreach($data as $row)
			{
				$str   .= '<tr>';
				$str   .= '<td>' . $row -> Command .'</td>';
				//$str .= '<td>' . $row -> Id .'</td>';
				$str   .= '<td>' . $row -> Image .'</td>';
				$str   .= '<td>' . self::getPorts($row->Ports) .'</td>';
				$str   .= '<td>' . StringHelper::timeAgo($row -> Created) .'</td>';				
				$str   .= '</tr>';
				
			}
			
			$str .= '</table> </div>';
		 }
		 else {
			 return 'No data found';
		 }
		 return $str;
		 
	}

	private static function getPorts($row)
	{
		if(empty($row)) return '';
		else {
			$str = '';
			foreach($row as $set)
			{
				$a = (array) $set;                                
				$str .= http_build_query($a, '', ' '). '<br/>';
			}
			return $str;
		}
	}

	public static function getStatus($json)
	{
		if(StringHelper::isJson($json))
		{
			$obj = json_decode($json);
			return self::getLabel($obj->status);
		}
		else {
			return self::getLabel('error');
		}
	}
	
	
	public static function displayCurrentCost($id, $data)
	{
		$str = '';
		$str = '<h5><span class="glyphicon glyphicon-tag"> </span> Cost Data</h5>';
		$data = (object) $data;
		if(isset($data->status) && $data->status == 'OK')
		{
			$costData = (array) $data->cost_data;

			$i=0;		
			foreach($costData as $row => $val)
			{
				$str .= '<div class="col-md-2 column">';
				if($i==0)
				$str .= '<div class="panel panel-default">';
				elseif($i==1)
				$str .= '<div class="panel panel-primary">';
				elseif($i==2)
				$str .= '<div class="panel panel-danger">';
				elseif($i==3)
				$str .= '<div class="panel panel-warning">';
				elseif($i==4)
				$str .= '<div class="panel panel-success">';
				elseif($i==5) {
				$str .= '<div class="panel panel-info">';
					$i=-1;
				}
				$str .= '<div class="panel-heading"><h3 class="panel-title text-center"><b>'. $val .'</b></h3></div>';
				$str .= '<div class="panel-body" style="min-height:8em">'. $row .'</div>';
				$str .= '</div>';
				$str .= '</div>';
				$i++;
				//$str .= $row . ' : ' . $val. ' | ';
			}		
			$str .= '<b>Total :' . $data->total .'</b><br>';
			$str .= '<span class="glyphicon glyphicon-calendar"></span> Current Month : '.$data->month;
			$str .= '<br/><span class="glyphicon glyphicon-calendar"></span>Current Month : '.StringHelper::timeAgo($data->lastUpdate);
		}
		else if(isset($data->status) && $data->status == 'error')
		{
				$str = $data->message;
		}
		return $str;
	}
	
	public static function getAccount($account)
	{
		return $account->name .':' . $account->profileType;
	}
	
	public static function getAuditTable($account, $reports)
	{
		if(!StringHelper::isJson($reports))
		{
			return  array('status' => 'error', 'message' => 'Error retrieving security audit report for '.$account->name);
		}
		else 
		{
			$reportsObj = json_decode($reports);
			if($reportsObj -> status == 'error')
			{
				return  array('status' => 'error', 'message' => 'Error retrieving security audit report for '.$account->name);
			}
			$reportsArr = $reportsObj->reports;
			$table = new stdClass();
			$arr = '';
			foreach($reportsArr as $row)
			{
				$table ->name = $account->name;
				$table ->accountId = $account->id;
				$table ->oid = $row->oid;
				$table -> report = 	URL::to('security/auditReport');
				$table -> Time = StringHelper::timeAgo($row->report_time);	
				$table -> Changed = $row->changed;
				$arr[] = $table;
				unset($table);
				$table = new stdClass();
			}
			return $arr;
		}
	}	
	
	public static function getServicesStatus($account)
	{
		$jobs = empty($account->jobs) ? '' : $account->jobs;
		$str = '';	
		$new_prob = array();
		if(!empty($jobs))
		{
			foreach ($jobs as $array) {
				if (!array_key_exists($array->operation, $new_prob)) {
					$new_prob[$array->operation] = $array;
				}
			}
			$jobs = $new_prob;
			$str = '<div class="table-responsive">  <table class="table table-bordered">';

			
			
			foreach($jobs as $job)
			{
				$temp_url = URL::full().'/'.$job->cloudAccountId.'/refresh';
				$str .= '<tr>';
				if($job -> operation==Lang::get('account/account.create_billing'))
					$str .= '<td> <i class="fa fa-cogs"></i> ' . $job -> operation. '</td>';	
				elseif($job -> operation==Lang::get('account/account.create_services'))
				 	$str .= '<td> <i class="fa fa-credit-card"></i> ' . $job -> operation. '</td>';
				else
					$str .= '<td> <i class="fa fa-lock"></i> ' . $job -> operation. '</td>';

								
				$str .= '<td>' . self::getLabel($job -> status). '</td>';				
				if(in_array($job->status, array(Lang::get('account/account.STATUS_IN_PROCESS'), 
																Lang::get('account/account.STATUS_STARTED'))))
				{
					$str .= '<td><a  href="'.$temp_url.'"> <span class="glyphicon glyphicon-refresh"></span> </a></td>';
				}
					
				$str .=  '<td> <i class="fa fa-calendar"></i> Last Update ' . $job -> updated_at. '</td>';
				
				$str .= '</tr>';
				
			}
			
			$str .= ' </table> </div>';
		}
		return $str;
		/*$jobData = ProcessJob::where('user_id', Auth::id())
						-> where('cloudAccountId', $account->id) 
						-> orderBy('created_at', 'desc')
						-> get();
		$str = '';				
		foreach($jobData as $row)
		{
			$str .= ucfirst($row->operation) . ':' . self::getLabel($row->status) . '<br/>';
		}				
		return $str;*/
		
	}




	public static function getPortPreferenceServicesStatus($portPreference)
	{
		$obj = json_decode($portPreference->toJson());
		$processJobs = ProcessJob::where('user_id', Auth::id())->where('cloudAccountId', $obj->cloudAccountId) ->where('operation', Lang::get('account/account.create_secgroup')) -> orderBy('created_at', 'desc') -> get();
		$processJobs = json_decode($processJobs->toJson());
		
		$jobs = empty($account->jobs) ? '' : $account->jobs;
		$str = '';	
		$new_prob = array();
		if(!empty($processJobs))
		{
			foreach ($processJobs as $array) {
				if (!array_key_exists($array->operation, $new_prob)) {
					$new_prob[$array->operation] = $array;
				}
			}
			$processJobs = $new_prob;
			$str = '<div class="table-responsive">  <table class="table table-bordered">';

			foreach($processJobs as $job)
			{
				$temp_url = URL::full().'/'.$job->cloudAccountId.'/refresh';
				$temp_url = str_ireplace(Lang::get('security/portPreferences.portPreference'),Lang::get('account/account.account'),$temp_url);
				
				$str .= '<tr>';
				$str .= '<td> <i class="fa fa-lock"></i> ' . $job -> operation. '</td>';

								
				$str .= '<td>' . self::getLabel($job -> status). '</td>';				
				if(in_array($job->status, array(Lang::get('account/account.STATUS_IN_PROCESS'), 
																Lang::get('account/account.STATUS_STARTED'))))
				{
					$str .= '<td><a  href="'.$temp_url.'"> <span class="glyphicon glyphicon-refresh"></span> </a></td>';
				}
				$str .=  '<td> <i class="fa fa-calendar"></i> Last Update ' . $job -> updated_at. '</td>';
								
				$str .= '</tr>';
				
			}
			
			$str .= ' </table> </div>';
		}
		return $str;
	}

}
