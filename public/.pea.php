<?php
/**
 * pea dispatcher
 * @version 0.3.0
 * @author Chris Rhoden
 * @copyright 2009 Chris Rhoden
 **/
 
session_start();
define('BASEDIR', dirname(dirname(__FILE__)));
define('CONFIGDIR', BASEDIR.'/config');


/* Short circuit static file requests */
if ($_SERVER['REQUEST_URI'] != '/' && file_exists(BASEDIR.'/public'.$_SERVER['REQUEST_URI'])){
    die(file_get_contents(BASEDIR.'/public'.$_SERVER['REQUEST_URI']));
}

$started = microtime();

// Autoloading offloaded to our autoloader
require_once '../pea/autoloader.class.php';
function __autoload($class){
    peaAutoLoader::Load($class);
}

/* Let's start by grabbing all of the necessary
     configuration as well as our pod class */
require_once '../pea/pod.class.php';
require_once '../pea/vendor/spyc.php';

$_pea_options = Spyc::YAMLLoad(CONFIGDIR.'/routes.yml');
/* ...and then pass in our options to the new pod */
$PEA_POD = new peaPod($_pea_options);

$_pea_modules = Spyc::YAMLLoad(CONFIGDIR.'/modules.yml');
/* tell the pod to load all of the modules */
foreach((array)$_pea_modules['active'] as $module){
	$PEA_POD->load_pea($module);
}
/* Let us not keep these around */
unset($_pea_modules); unset($_pea_options);

// define the request
$uri = explode('?',$_SERVER['REQUEST_URI'],2);
$uri = $uri[0];
define('REQUEST_URI', $uri);
unset($uri);

/* finally, dispatch based on request uri */
ob_start();
$page_content = $PEA_POD->run(REQUEST_URI);
file_put_contents('php://stderr', ob_get_clean());
echo $page_content;
$ended = microtime();
file_put_contents('php://stderr', "\n\nRendered '" . REQUEST_URI . '\' in ' . ($ended - $started) . " seconds \n\n" );