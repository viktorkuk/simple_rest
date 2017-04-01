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
        parse_str(file_get_contents("php://input"),$putVars);
        $book = new BookModel($putVars['ISBN']);
        $book->setData($putVars);
        $book->save();
        $this->result = array(
            'status' => 'ok'
        );
    }
    
    public function postAction($args){
        $this->result = array(
            'status' => 'ok'
        );
    }
    
    public function deleteAction($args){
        
        $status = 'ok';
        
        $entryId = $args[0];
        $book = new BookModel($entryId);
        $book->delete();
        
        $this->result = array('status' => $status);
    }
    
    public function optionsAction($args){                
        $this->result = array('status' => 'ok');
    }

    
}

