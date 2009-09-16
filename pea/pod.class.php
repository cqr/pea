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
	private $loaded_modules = array();
	
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
		if ($this->module_exists($module)) {
		    ${'_pea_'.$module.'_manifest'} = Spyc::YAMLLoad(BASEDIR."/app/modules/$module/manifest.yml");
		    if (array_key_exists('depends', (array)${'_pea_'.$module.'_manifest'})) {
		        foreach((array)${'_pea_'.$module.'_manifest'}['depends'] as $dependency){
		            if (in_array($dependency, $this->loaded_modules)) {
		                return true;
		            } elseif ($this->module_exists($dependency)) {
		                $this->load_pea($dependency);
		            } else {
		                die("Unresolved dependencies: $module depends on $dependency, which was not found.");
		            }
		        }
		    }
		    foreach((array)${'_pea_'.$module.'_manifest'}['require'] as $require_file){
		        require_once BASEDIR."/app/modules/$module/$require_file";
		    }
		    array_push($this->loaded_modules, $module);
		    return true;
		}
	}
	
	private function module_exists($module)
	{
	    return file_exists(BASEDIR."/app/modules/$module/manifest.yml");
	}
}
?>