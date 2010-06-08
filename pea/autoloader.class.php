<?php
class peaAutoLoader {
    
    private static $loaders = array();
    
    public static function Load(){
        foreach(self::$loaders as $loader){
            if (call_user_func_array($loader, func_get_args())) {
                return true;
            }
        }
    }
    
    public static function AddLoader($callback) {
        array_push(self::$loaders, $callback);
    }
    
}