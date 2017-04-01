<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author victor
 */
class DB {
    
    public $db;

    private static $instance;

    private function __construct() {
        $this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        $this->db->set_charset("utf8");
        if ($this->db->connect_error) {
            die('Connect Error (' . $this->db->connect_errno . ') ' . $this->db->connect_error);
        };
    }

    public static function getInstanse(){
        if (null === self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone(){
    }

    public function fetchAll($table, $fields = array('*'), $object = false){        
        return $this->rawQuery('SELECT '.implode(', ', $fields)." FROM `$table`");
    }
    
    public function fetchCount($table){
        $row = $this->rawQuery("SELECT COUNT(*) as count FROM `$table`");
        return $row[0]['count'];
    }
    
    public function fetchPart($table, $limit, $offset = 0, $fields = array('*'), $object = false){
        return $this->rawQuery('SELECT '.implode(', ', $fields)." FROM `$table` LIMIT $limit OFFSET $offset");
    }

    public function fetchRow($table, $id, $keyField = 'id', $fields = array('*'), $object = false){
        $data = $this->rawQuery('SELECT '.implode(', ', $fields)." FROM `$table` WHERE $keyField = ".intval($id),$object);
        if($data){
            return $data[0];
        }else{
            return false;
        }    
    }
    
    public function fecthKeys($table){
        $res = $this->db->query("SHOW COLUMNS FROM `$table`");
        while($result = mysqli_fetch_assoc($res)){
            $columns[$result['Field']] = '';
        }
        return $columns;
        
    }

    public function insertRow($table, $data){
        $fields = array_keys($data);
        $sql = "INSERT INTO ".$table."
                (`".implode('`,`', $fields)."`)
                VALUES('".implode("','", $this->escape($data))."')";
        $this->rawQuery($sql);
        
        return $this->db->insert_id;
    }


    public function updeteRow($table, $data, $id, $keyField = 'id'){
        $sql = "UPDATE `".$table."` SET ";
        $sets = array();
        foreach($data as $column => $value){
             $sets[] = "`".$column."` = '".$this->escape($value)."'";
        }
        $sql .= implode(', ', $sets);
        $sql .= " WHERE `$keyField` = ".intval($id);
        return $this->rawQuery($sql);
    }

    public function delete($table, $id, $keyField = 'id'){
        return $this->rawQuery("DELETE FROM `$table` WHERE `$keyField` = ".intval($id));
    }


    public function rawQuery($query,$isObject = false){
        $data = array();

        $res = $this->db->query($query);

        if ($res === FALSE) {
            printf("Mysql query error: %s<br>", $this->db->error);
            die();
        }elseif($res === TRUE){
            return $res;
        }else{
            if($isObject){
                while ($row = $res->fetch_object()) {
                   $data[] = $row;
                }
            }else{
                while ($row = $res->fetch_assoc()) { 
                   $data[] = $row;
                }
            }
        }
        
        return $data;
    }

    public function escape($data){
       if( !is_array( $data )){
           $data = $this->db->real_escape_string( $data );
       }else{
           $data = array_map(array($this, 'escape'), $data );
       }
       return $data;
    }
    
}
