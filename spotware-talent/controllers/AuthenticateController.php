<?php

class AuthenticateController extends BaseRestController{

    public function getAction($args){

        $this->result = array(
            'authenticate' => AuthenticateModel::isLogin()
        );
    }
    
    public function postAction($args){

        $data = array('status' => '', 'error' => '');
        
        if(isset($_POST['hashsum'])){
            if(AuthenticateModel::login($_POST['hashsum'])){
                $data['status'] = 'ok';
            }else{
                $data['error'] = 'wrong login data';
            }
        }else{
             $data['error'] = 'no login data';
        }
        
        $this->result = $data;

    }
    
    public function deleteAction($args){        
        AuthenticateModel::logout();        
        $this->result = array('status' => 'ok');
    }
    
    public function optionsAction($args){                
        $this->result = array('status' => 'ok');
    }

    
}

