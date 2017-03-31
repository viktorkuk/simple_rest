<?php

class BaseRestController{
    
    public $result;
    
    public function __construct(){
        $this->result = array();
    }
    
    public function __destruct(){
      header('Content-Type: application/json');
      echo json_encode($this->result);
    }
    
    
}