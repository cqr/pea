<?php
// I grant that this is entirely unlike PHP, in that it is
// wholely readable. This may get worked out in a future
// revision.

$_pea_options = array(
	
	// Your base url. If, for instance, pea is installed
	// to http://example.com/pea/, you should use /pea/
	'base_url' => '/',
	
	// These are your routes. The markup is something like
	// format;overrides, and placing a $ before a name means
	// that it will be sent as a value to the dispatcher.
	// EXAMPLES:
	//	'/cart;$controller=shopping,$action=show_cart'
	//	'/$action/$id';$controller=blog'
	'routes' => array(
		'/$controller/$action/$id',
		'/;$controller=welcome',
	),
	
	// Database connection credentials
	'db' => array(
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => ''
	),
	
	'env' => array(
		'clapton' => false,
	),

	);
?>
