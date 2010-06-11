<?php

class peaMessenger {
    
    private static $queued, $listeners;
    
    static function Send($message) {
        echo "\n\nRecieved message '$message'\n";
        $values = func_get_args();
        array_shift($values);
        if (isset(self::$listeners[$message])){
            foreach(self::$listeners[$message] as $listener){
                echo "\nSending to: $listener\n";
                call_user_func_array($listener, $values);
            }
        }
        if (!isset(self::$queued[$message])){
            self::$queued[$message] = array();
        }
        array_push(self::$queued[$message], $values);
    }
    
    static function RegisterListener($message, $listener) {
        if (!isset(self::$listeners[$message])) {
            self::$listeners[$message] = array();
        }
        array_push(self::$listeners[$message], $listener);
        if (isset(self::$queued[$message])){
            foreach( self::$queued[$message] as $values ) {
                call_user_func_array($listener, $values);
            }
        }
    }
}