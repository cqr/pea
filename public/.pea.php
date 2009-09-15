<?php
/**
 * pea dispatcher
 * @version 0.3.0
 * @author Chris Rhoden
 * @copyright 2009 Chris Rhoden
 **/

/* Let's start by grabbing all of the necessary
     configuration as well as our pod class */
require_once '../pea/global_utilities.php';
require_once '../pea/pod.class.php';
require_once '../pea/vendor/spyc.php';

$_pea_options = Spyc::YAMLLoad(CONFIGDIR.'/routes.yml');
/* ...and then pass in our options to the new pod */
$_pea_pod = new peaPod($_pea_options);

$_pea_modules = Spyc::YAMLLoad(CONFIGDIR.'/modules.yml');
/* tell the pod to load all of the modules */
foreach((array)$_pea_modules['active'] as $module){
	$_pea_pod->load_pea($module);
}

/* finally, dispatch based on request uri */
$_pea_pod->run(REQUEST_URI);
