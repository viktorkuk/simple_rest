<?php

class BooksController extends BaseRestController{

    public function getAction($args){
        
        $page = isset($args[0]) ? $args[0] : 0;
        $this->result = array(
            'books' => BookModel::getAllBooksPart(ITEMS_ON_PAGE,$page),
            'pages' => ceil(BookModel::getBooksCount()/ITEMS_ON_PAGE)
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
        echo json_encode(array(
            'status' => 'ok'
        ));
    }

    
}

