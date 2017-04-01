<?php

class RatingsController extends BaseRestController{

    public function getAction($args){
        $entryId = $args[0];
        $book = new BookModel($entryId);
        $this->result = array(
            'ratings' => $book->getRatings()
        );
    }
}

