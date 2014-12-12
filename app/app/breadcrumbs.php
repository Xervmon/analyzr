<?php

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.home'), function ($breadcrumbs) {
	$breadcrumbs->push(ucfirst(Lang::get('breadcrumb/breadcrumb.home')), URL::to('/'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.account'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(ucfirst(Lang::get('breadcrumb/breadcrumb.account')), URL::to('account/'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.CreateAccount'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.account'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.CreateAccount'), URL::to('account/create'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.EditProfile'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(ucfirst(Lang::get('breadcrumb/breadcrumb.EditProfile')), URL::to('user'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.EditAccount'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.account'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.EditAccount'), URL::to('account/{account}/edit'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.SecurityGroups'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.account'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.SecurityGroups'), URL::to('assets/{account}/SecurityGroups'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.AWS_Details'), function ($breadcrumbs, $id) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.account'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.AwsInfo'), URL::to('assets/' . $id . '/AwsInfo'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.CollectionData'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.account'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.CollectionData'), URL::to('account/{account}/CollectionData'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.ChartsData'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.account'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.ChartsData'), URL::to('account/{account}/ChartsData'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.InstanceInfo'), function ($breadcrumbs, $id) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.AWS_Details'), $id);
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.InstanceInfo'), URL::to('assets/' . $id . '/EC2'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.EbsInfo'), function ($breadcrumbs, $id) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.AWS_Details'), $id);
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.EbsInfo'), URL::to('assets/' . $id . '/EBS'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.SecurityGroupInfo'), function ($breadcrumbs, $id) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.AWS_Details'), $id);
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.SecurityGroupInfo'), URL::to('assets/' . $id . '/SecurityGroups'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.KpInfo'), function ($breadcrumbs, $id) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.AWS_Details'), $id);
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.KpInfo'), URL::to('assets/{account}/KeyPairs'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.SubnetsInfo'), function ($breadcrumbs, $id) {
 $breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.AWS_Details'), $id);
 $breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.SubnetsInfo'), URL::to('assets/' . $id . '/Subnets'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.TagsInfo'), function ($breadcrumbs, $id) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.AWS_Details'), $id);
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.TagsInfo'), URL::to('assets/'.$id.'/Tags'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.TaggedCost'), function ($breadcrumbs, $id) {
 	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.TagsInfo'), $id);
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.TaggedCost'), URL::to('assets/{account}/TaggedCost'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.VpcsInfo'), function ($breadcrumbs, $id) {
 $breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.AWS_Details'), $id);
 $breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.VpcsInfo'), URL::to('assets/' . $id . '/VPC'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.AuditReport'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.CreateAccount'), URL::to('account/create'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.AuditReport'), URL::to('security/AuditReport'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.PortPreferences'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.PortPreferences'), URL::to('security/portPreferences/'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.AddPolicy'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.PortPreferences'), URL::to('security/portPreferences/'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.AddPolicy'), URL::to('security/portPreferences/create'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.PortPreferencesInfo'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.PortPreferences'), URL::to('security/portPreferences/'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.PortPreferencesInfo'), URL::to('security/portPreferences/{portPreference}/portInfo'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.EngineLog'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.EngineLog'), URL::to('enginelog/'));
});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.ServiceStatus'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.ServiceStatus'), URL::to('ServiceStatus/'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.Reserved'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.ReservedInstancePricing'), URL::to('Reserved/'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.OndemandInstancePricing'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.OndemandInstancePricing'), URL::to('Ondemand/'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.EC2Products'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.EC2Products'), URL::to('EC2Products/'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.Ticket'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.Ticket'), URL::to('ticket/'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.AddTicket'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.Ticket'), URL::to('ticket/'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.AddTicket'), URL::to('ticket/create'));
});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.DataSecurity'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.DataSecurity'), URL::to('data-security'));

});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.Roadmap'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.Roadmap'), URL::to('roadmap'));

});
Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.CloudExperts'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.CloudExperts'), URL::to('cloudExperts'));

});


Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.Error500'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.Error500'), URL::to('Error500/'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.Error403'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.Error403'), URL::to('Error403/'));

});

Breadcrumbs::register(Lang::get('breadcrumb/breadcrumb.Error404'), function ($breadcrumbs) {
	$breadcrumbs->parent(Lang::get('breadcrumb/breadcrumb.home'));
	$breadcrumbs->push(Lang::get('breadcrumb/breadcrumb.Error404'), URL::to('Error404/'));

});