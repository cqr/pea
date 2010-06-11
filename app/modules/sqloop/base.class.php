<?php
class SqloopBase{
    
    private $user_config = array();
    
    private static $connection, $connected; 
    
    static function Connect($connection)
    {
        self::$connection = $connection;
        self::$connected = true;
    }
    
    public function __construct()
    {
        $args = func_get_args();
        if (array_key_exists(0, $args) && is_int($args[0])){
            $this->id = array_shift($args);
        }
        if (array_key_exists(0, $args) && is_array($args[0])) {
            $this->user_config = array_shift($args);
        }
        if (!array_key_exists('ensure_connected', $this->user_config) || !$this->user_config['ensure_connected']) {
            self::EnsureConnected();
        }
    }
    
    public static function ClassName(){
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
    
    public static function AutoLoad($class)
    {
        if(file_exists(BASEDIR."/app/models/$class.php")) {
            require(BASEDIR."/app/models/$class.php");
            return true;
        }
        return false;
    }
    
}