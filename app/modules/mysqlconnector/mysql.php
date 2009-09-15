<?php
class MySQLConnector
{

	private $server, $username, $password, $database;
	var $result;
	var $count;
	
	function __construct($server, $username, $password, $database )
	{
		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		mysql_connect($server,$username,$password);
		@mysql_select_db($database) or 
				die("Unable to connect to database");
	}
	
	function sendRequest( $request )
	{
	    $this->result=mysql_query($request);
		if (is_resource($this->result)) {
			$this->count = mysql_numrows($this->result);
		} else {
			$this->count = 0;
		}
		return $self;
	}

	function getResult($sql = NULL)
	{
		if (!isset($sql)) {
			$this->sendRequest($sql);
			return $this->getResult();
		}
		
		if (!isset($this->result))
		{
			return $this->result;
		}
		
		return false;
	}

	function getCount($sql = NULL)
	{
		if (!isset($sql))
		{
			$this->sendRequest($sql);
			return $this->count;
		}

		if (!isset( $this->count )) {
			return $this->count;
		}

		return false;
	}	
}
