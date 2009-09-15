<?php 
class PeaBaseController
{
    protected $params;
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
        if ($template == NULL) {
            $template = $this->params['action'];
        }
        if (!preg_match('/\//', $template)) {
            $template = $this->params['controller'].'/'.$template;
        }
        if (file_exists(BASEDIR."/app/views/$template.php")) {
            $this->rendered = render_template($template, $this->variables);
        } else {
            $this->rendered = "Error: View file $template.php does not exist!";
        }
        return $this->rendered;
    }
    protected function set($variable_name, $value)
    {
        $this->variables[$variable_name] = $value;
    } 
}