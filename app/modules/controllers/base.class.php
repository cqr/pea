<?php 
class BaseController
{
    protected $params;
    protected static $instance;
    private $rendered = false;
    private $variables = array();
    public function run($options)
    {
        
        if (!method_exists($this, $options['action'])) {
            return "Error: your {$options['controller']}Controller does not have an action called {$options['action']}.";
      
        } else {
            $this->params = $options;
            $this->$options['action']();
            if (!$this->rendered) {
                $this->renders();
            }
            return $this->rendered;
        }
        
    }
    protected function renders($template = NULL)
    {
        if ($this->rendered) {
            $this->rendered = 'You cannot call render more than once. Please return after rendering.';
            return $this;
        }
        if ($template == NULL) {
            $template = $this->params['action'];
        }
        if (!preg_match('/\//', $template)) {
            $template = $this->params['controller'].'/'.$template;
        }
        peaMessenger::Send('render_template', $template);
        return $this;
    }
    
    protected function set($variable_name, $value)
    {
        $this->variables[$variable_name] = $value;
        return $this;
    }
    
    public function __construct()
    {
        self::$instance = $this;
    }
    
    public static function TemplateRendered($content)
    {
        self::GetInstance()->rendered = $content;
    }
    
    public static function GetInstance($classname = NULL)
    {
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            if (!isset($classname)) {
                $classname = self;
            }
            return new $classname;
        }
    }
    
    public static function AutoLoad($classname)
    {
        if(file_exists(BASEDIR."/app/controllers/$classname.php")){
            require(BASEDIR."/app/controllers/$classname.php");
            return true;
        }
        return false;
    }
}