<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewsModel
 *
 * @author victor
 */


/*
ISBN	varchar(13) []	 
Book-Title	varchar(255) NULL	 
Book-Author	varchar(255) NULL	 
Year-Of-Publication	int(10) unsigned NULL	 
Publisher	varchar(255) NULL	 
Image-URL-S	varchar(255) NULL	 
Image-URL-M	varchar(255) NULL	 
Image-URL-L	varchar(255) NULL	  
 */
class BookModel extends BaseModel {
    
    const DB_TABLE = 'BX-Books';
    const KEY_FIELD = 'ISBN';

    public function __construct($id = false) {
        parent::__construct(self::DB_TABLE, $id, self::KEY_FIELD);
    }
    
    public static function getAllBooks(){
        return parent::getAllItems(self::DB_TABLE);
    }
    
    public static function getAllBooksPart($limit, $part = 0){
        $offset = $limit * $part;   
        return parent::getItemsPart(self::DB_TABLE, $limit, $offset);
    }
    
    public static function getBooksCount(){
        return parent::getItemsCount(self::DB_TABLE);
    }
    
    public function getRatings(){
        return $this->db->rawQuery("SELECT SUBSTRING_INDEX(`Location` , ', ', -1) as country,
                                        COUNT(`BX-Users`.`User-ID`) as votes,
                                        AVG(`Book-Rating`) as rating 
                                    FROM `BX-Book-Ratings` 
                                        LEFT JOIN `BX-Users` ON `BX-Book-Ratings`.`User-ID` = `BX-Users`.`User-ID` 
                                    WHERE `BX-Book-Ratings`.`ISBN` = '{$this->data['ISBN']}' 
                                    GROUP BY country");
    }
    
    public function getIsbn(){
        return $this->data['ISBN'];
    }
    
    public function setIsbn($isbn){
        $this->data['ISBN'] = $isbn;
    }
    
    public function getTitle(){
        return $this->data['Book-Title'];
    }
    
    public function setTitle($title){
        $this->data['Book-Title'] = $title;
    }
    
    
    public function save() {
        unset($this->data['date']);
        parent::save();
    }
    
    
}
