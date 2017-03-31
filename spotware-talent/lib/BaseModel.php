<?php

class BaseModel{

    protected $db = null;
    protected $dbTable;
    protected $id;
    protected $keyField;
    public $data;

    public function __construct($dbTable, $id = false, $keyField = 'id'){
        $this->db = DB::getInstanse();
        $this->dbTable = $dbTable;
        $this->id = $id;        
        $this->keyField = $keyField;
        
        if(!$id){
            $this->data = $this->db->fecthKeys($this->dbTable);
        }else{
            $this->data = $this->db->fetchRow($this->dbTable,$this->id,$this->keyField);
        }
        
        if(!$this->data){
            throw new Exception('No such record');
        }
    }

    public function save(){
        if($this->id){
            return $this->db->updeteRow($this->dbTable, $this->data, $this->id, $this->keyField);
        }else{
            return $this->id = $this->db->insertRow($this->dbTable, $this->data);
        }
    }
    
    public function delete(){
        $this->db->delete($this->dbTable, $this->id, $this->keyField);
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getData(){
        return $this->data;
    } 
    
    public function setData($data){
        $dataDiff = array_diff_key($this->data,$data);
        if(!empty($dataDiff)){
            throw new Exception('wrong data for save!');
        }
        
        $this->data = $data;
        
        return $this->id;
    }
    
    public static function getAllItems($dbTable){
        $db = DB::getInstanse();
        return $db->fetchAll($dbTable);
    }
    
    public static function getItemsPart($dbTable, $limit, $offset = 0){
        $db = DB::getInstanse();
        return $db->fetchPart($dbTable, $limit, $offset);
    }
  
    public static function getItemsCount($dbTable){
        $db = DB::getInstanse();
        return $db->fetchCount($dbTable);
    }
}