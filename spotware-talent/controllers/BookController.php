<?php

class BookController extends BaseRestController{

    public function getAction($args){
        $entryId = $args[0];
        $book = new BookModel($entryId);
        $this->result = array(
            'book' => $book->getData(),
            'ratings' => $book->getRatings()
        );
    }
    
    public function putAction($args){
        $this->checkAuthentication();
        parse_str(file_get_contents("php://input"),$putVars);
        $book = new BookModel($putVars['ISBN']);
        $book->setData($putVars);
        $book->save();
        $this->result = array(
            'status' => 'ok'
        );
    }
    
    public function postAction($args){
        $this->checkAuthentication();
        $book = new BookModel();
        $book->setData($_POST);
        $book->save();
        $this->result = array(
            'status' => 'ok'
        );
    }
    
    public function deleteAction($args){
        $this->checkAuthentication();
        $status = 'ok';
        $entryId = $args[0];
        $book = new BookModel($entryId);
        $book->delete();
        $this->result = array('status' => $status);
    }
    
    public function optionsAction($args){                
        $this->result = array('status' => 'ok');
    }
    
    private function checkAuthentication(){
        if(!AuthenticateModel::isLogin()){
            $this->result = array('status' => 'Unauthorized');
            header('HTTP/1.0 401 Unauthorized');
            die();
        }
    }
}

