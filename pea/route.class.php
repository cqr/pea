<?php
/**
 * peaRoute class for pea
 * @version 0.1.0
 * @author chrisrhoden
 * @copyright 2009 Chris Rhoden
 **/

/**
 * peaRoute Class
 *
 * @package pea
 * @author chrisrhoden
 **/
class peaRoute
{
	
	private $route, $size;
	private $values = array();
	private $defaults = array('action'=>'index');
	
	function __construct($r)
	{
		$r = explode(';',$r,2);
		$this->route = explode('/',array_shift($r));
		while(count($this->route) && $this->route[0] == NULL){
		    array_shift($this->route);
		}
		$this->size = count($this->route);
		foreach(explode(',',array_shift($r)) as $pair) {
		    $pair = explode('=',$pair);
		    $this->defaults[substr($pair[0],1)] = $pair[1];
		}
		while(count($this->defauts) && $this->defaults[0] == NULL){
		    array_shift($this->defaults);
		}
	}
	
	function matches($uri)
	{
	    if ($this->size == 0) return true;
	    $uri = explode('/', $uri);
	    array_shift($uri);
	    if($this->size >= count($uri)){
	        $this->values = array();
	        foreach($uri as $index => $section){
	            if($this->route[$index][0] == '$') {
	                $this->values[substr($this->route[$index],1)] = $section;
	            } elseif($this->route[$index] != $section) {
	                return false;
	            }
	        } return true;
	    } else { return false; }
	}
	
	function __get($var)
	{
	    if(isset($this->values[$var])) return $this->values[$var];
	    if(isset($this->defaults[$var])) return $this->defaults[$var];
	    return $this->__call($var, NULL);
	}
	
	function variables()
	{
	    $ret = array();
	    foreach(array_merge($this->defaults, $this->values) as $key => $value){
	        if($value != NULL && $value != '') {
	            $ret[$key] = $value;
	        }
	    }
	    return $ret;
	}
	
	function __call($function, $params)
	{
	    if(method_exists($this, $function)) return $this->$function();
	}
	
	static function findMatch($needle, $haystack)
	{
	    foreach($haystack as $potential_route){
	        $potential_route = new peaRoute($potential_route);
	        if ( $potential_route->matches($needle) ){
	            return $potential_route;
            }
        }
    return false;
    }

}
