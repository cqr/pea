<?php
class SqloopBase{
    
    private static $connection, $connected; 
    
    static function Connect($connection)
    {
        self::$connection = $connection;
        self::$connected = true;
    }
    
    public function __construct()
    {
        self::EnsureConnected();
        echo get_class($this);
    }
    
    public static function EnsureConnected()
    {
        if (!self::$connected){
            peaMessenger::Send('database_connect');
        }
        if (!self::$connected) {
            die('unable to connect to database');
        }
    }
    
}