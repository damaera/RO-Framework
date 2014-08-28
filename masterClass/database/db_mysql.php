<?php

class db_mysql{
    private $con;
    public $query;
    public $debug = false;
    
    public function inputFilter($field){
        $return = addslashes($field);
        return $return;
    }
    
    function __construct($host,$database,$user,$pass){
        $this->con = mysql_connect($host,$user,$pass);
        mysql_select_db($database,$this->con);
    }
    
    public function prepare($query){
        $this->query = $query;
    }
    
    public function bindValue($key,$value){
        $query = str_replace($key,"'".$this->inputFilter($value)."'",$this->query);
        $this->query = $query;
    }
    public function execute(){
        if($this->debug)echo $this->query."<br />";
        return mysql_query($this->query,$this->con);
    }
    
    public function fetchAll(){
        $sql = $this->execute($this->query);
        $data = array();
        if(!empty($sql)){
            while($row = mysql_fetch_assoc($sql)){
                $data[] = $row;
            }
        }
        return $data;
    }
    
    public function fetch(){
        $sql = $this->execute($this->query);
        return mysql_fetch_assoc($sql);
    }
    
    public function lastInsertId(){
        return mysql_insert_id();
    }
    
    public function numRow(){
        $sql = $this->execute($this->query);
        return mysql_num_rows($sql);
    }
}

?>