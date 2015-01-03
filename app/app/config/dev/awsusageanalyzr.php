<?php


/*
 * @app.route("/authenticate", methods=["POST"])
@app.route("/register", methods=["POST"])
@app.route("/run", methods=["POST"])
@app.route("/instance", methods=["POST"])
@app.route("/getDeploymentStatus/<job_id>", methods=["POST"])
@app.route("/getLog/<job_id>", methods=["POST"])
@app.route("/uploadKey", methods=["POST"])
@app.route("/downloadKey", methods=["POST"])
 */
 
 
return array(
	'endpoint_ip'  		  => 'http://104.131.38.159:5050',
	'register'            => '/register',
	'authenticate'        => '/authenticate',
	'create_billing'      => '/create_billing',
	'getDeploymentStatus' => '/getDeploymentStatus',
	'getStatusOfAllDeployments' => '/getStatusOfAllDeployments',
	'GetCurrentCost'      => '/GetCurrentCost',
	'Collection'          => '/Collection',
	'GetCost'             => '/GetCost',
	'removeUsername'      => '/removeUsername',
	'create_secgroup'     => '/create_secgroup',
	'SecgroupReport'      => '/SecgroupReport',
	'create_audit'        => '/create_audit',
	'auditReports'        => '/auditReports',
	'auditReport'         => '/auditReport',
	'create_services'     => '/create_services',
	'serviceSummary'      => '/serviceSummary',
	'getLog' 			  => '/getLog',
	'getCurrentTaggedcost' 	=> '/getCurrentTaggedcost'
	'setBudget'			  	=>'/setBudget',
	'getBudgetStatus'	  	=>'/getBudgetStatus',
	'create_cloudTrail'	  	=>'/create_cloudTrail',
	'cloudTrailCollection'	=>'/cloudTrailCollection',
	'get_cloudTrail'	   	=>'/get_cloudTrail',
);

