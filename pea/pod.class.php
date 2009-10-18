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
	}
	
	function run($uri)
	{
	    $route = peaRoute::findMatch($uri, $this->options['routes']);
	    if ( $route && $route->controller != NULL ){
	        $controller = strtolower($r->controller) . "Controller";
	        $controller = new $controller;
	        $page = $controller->run(array_merge($_GET, $_POST, $r->variables));
	        echo $page;
	        return;
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
		    if (array_key_exists('autoloader', (array)${'_pea_'.$module.'_manifest'})) {
		        foreach((array)${'_pea_'.$module.'_manifest'}['autoloader'] as $autoloader){
		            
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