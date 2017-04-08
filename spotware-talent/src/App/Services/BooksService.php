<?php

namespace App\Services;

class BooksService
{
    const DB_TABLE = '`BX-Books`';
    const KEY_FIELD = 'ISBN';
    const PAGE_LIMIT = 30;
    
    protected $db;

    public function __construct($app)
    {
        $this->db = $app["db"];
    }

    public function getOne($id)
    {
        return $this->db->fetchAssoc("SELECT * FROM ".self::DB_TABLE." WHERE ".self::KEY_FIELD."=?", [(string) $id]);
    }

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM ".self::DB_TABLE);
    }
    
    public function getPage($page)
    {
        $offset = self::PAGE_LIMIT * intval($page);
        return $this->db->fetchAll("SELECT * FROM ".self::DB_TABLE." LIMIT ".self::PAGE_LIMIT." OFFSET ".$offset);
    }

    public function save($book)
    {
        $this->db->insert(self::DB_TABLE, $this->quoteData($book));
        return $this->db->lastInsertId();
    }

    public function update($id, $book)
    {
        return $this->db->update(self::DB_TABLE, $this->quoteData($book), array(self::KEY_FIELD => $id));
    }

    public function delete($id)
    {
        return $this->db->delete(self::DB_TABLE, array(self::KEY_FIELD => $id));
    }
    
    public function rowCount()
    {
        return $this->db->fetchAssoc("SELECT COUNT(*) AS count FROM ".self::DB_TABLE)["count"];
    }
    
    public function bookRating($id)
    {
        return $this->db->fetchAll("SELECT SUBSTRING_INDEX(`Location` , ', ', -1) as country,
                                        COUNT(`BX-Users`.`User-ID`) as votes,
                                        AVG(`Book-Rating`) as rating 
                                    FROM `BX-Book-Ratings` 
                                        LEFT JOIN `BX-Users` ON `BX-Book-Ratings`.`User-ID` = `BX-Users`.`User-ID` 
                                    WHERE `BX-Book-Ratings`.`ISBN` = ? 
                                    GROUP BY country",[(string) $id]);
    }
    
    private function quoteData($data)
    {
        $dataQuoted = array();
        foreach ($data as $key => $val) {
            $dataQuoted["`$key`"] = $val;
        }
        return $dataQuoted;
    }

}
