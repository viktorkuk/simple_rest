<?php

class BookController extends BaseRestController{

    public function getAction($args){
        $entryId = $args[0];
        $book = new BookModel($entryId);
        
        $this->result =array(
            'book' => $book->getData(),
            'ratings' => $book->getRatings()
        );
    }
    
    public function putAction($args){
        echo json_encode(array(
            'status' => 'ok'
        ));
    }
    
    public function postAction($args){
        echo json_encode(array(
            'status' => 'ok'
        ));
    }
    
    public function deleteAction($args){
        
        $status = 'ok';
        
        $entryId = $args[0];
        $book = new BookModel($entryId);
        $book->delete();
        
        $this->result = array('status' => $status);
    }

    
}

