<?php
class RestRouter {
    
    private $controller;
    private $method;
    private $args = array();
    
    public function __construct(){
        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        $explodedUri = explode('/', $uri);
        
        $this->controller = ucfirst(strtolower(empty($explodedUri[0]) ? DEFAULT_CONTROLLER : $explodedUri[0])).'Controller';
        $this->method = strtolower(empty($method) ? DEFAULT_METHOD : $method).'Action';
        $this->args = array_slice($explodedUri, 1);
    }
    
    public function route(){
        if(is_callable(array($this->controller, $this->method))){
            //call_user_func_array(array($this->controller, $this->action), $this->args);
            $controllerClass = new $this->controller(); 
            //$action = $this->action;
            $controllerClass->{$this->method}($this->args);
        }else{  
            header('HTTP/1.0 404 Not Found');
            echo "<h1>Error 404 Not Found</h1>";
            echo "The page that you have requested could not be found.";
            exit();
        }
    }
    
    
}
