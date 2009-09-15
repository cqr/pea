<?php
/**
 * peaPod class for pea
 * @version 0.2.0
 * @author chrisrhoden
 * @copyright 2009 Chris Rhoden
 **/

// This class requires peaRoute
require_once(BASEDIR.'/pea/route.class.php');
require_once(BASEDIR.'/pea/baseController.class.php');

/**
 * peaPod Class
 *
 * @package pea
 * @author Chris Rhoden
 **/
class peaPod
{
	
	private $options;
	
	function __construct($opts)
	{
		$this->options = array();
		foreach($opts as $opt => $val){
			$this->options[$opt] = $val;
		}
		for($i=0; $i< count($this->options['routes']); $i++) {
		    $this->options['routes'][$i] = new peaRoute($this->options['routes'][$i]);
		}
	}
	
	function run($uri)
	{
		foreach($this->options['routes'] as $r){
			if ($r->matches($uri)) {
			    if ($r->controller != NULL) {
			        $controller = strtolower($r->controller) . "Controller";
			        $controller = new $controller;
			        $page = $controller->run(array_merge($_GET, $_POST, $r->variables));
			        //ob_start('ob_gzhandler');
			        echo $page;
			        return;
			    }
			}
		}
		die("Error: no route matches '$uri'.");
	}
	
	function load_pea($module)
	{
		if(file_exists(BASEDIR."/modules/$module/manifest.yml")){
		    ${'_pea_'.$module.'_manifest'} = Spyc::YAMLLoad(BASEDIR."/modules/$module/manifest.yml");
		    foreach((array)${'_pea_'.$module.'_manifest'}['require'] as $require_file){
		        require_once BASEDIR."/modules/$module/$require_file";
		    }
		}
	}
}
?>