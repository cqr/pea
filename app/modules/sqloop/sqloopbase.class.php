<?php
class SqloopBase{
    
    private static $connection, $connected; 
    
    static function Connect($connection){
        self::$connection = $connection;
        self::$connected = true;
    }
    
    public function __call($message, $arguments) {
        if (!self::$connected){
            peaMessenger::Send('database_connect');
        }
        if (!self::$connected) {
            die('unable to connect to database');
        }
    }
}