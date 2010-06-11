<?php
class NestableTemplatesBase
{
    static $instance;
    public function __construct()
    {
        
    }
    
    public function set_local_variables($variables)
    {
        
    }
    
    public function set_functions()
    {
        foreach(get_class_methods($this) as $function_name){
            if (!function_exists($function_name)) {
                eval('function '.$function_name.'(){
                    call_user_func_array(array(NestableViewBase::GetInstance(),'.$function_name.'), func_get_args() );
                }');
            }
        }
    }
    
    public static function GetInstance($classname)
    {
        if (isset(self::$instance)) {
            return $instance;
        } else {
            if (file_exists(BASEDIR.'/app/view_helpers/'.$classname.'.php')){
                return self::$instance = new $classname;
            } else {
                return new self;
            }
        }
    }
    
    public static function RenderTemplate($path_to_file)
    {
        echo "\n\nRendering: '$path_to_file'\n\n";
        ob_start();
        include(BASEDIR.'/app/views/'.$path_to_file.'.php');
        $main_body = ob_get_clean();
        $chain = explode('/', $path_to_file);
        array_pop($chain);
        while(count($chain)){
            ob_start();
            include(BASEDIR.'/app/views/'.implode('/', $chain).'/template.php');
            $main_body = str_replace('%CHILD%', $main_body, ob_get_clean());
            array_shift($chain);
        }
        peaMessenger::Send('template_rendered', $main_body);
    }
}