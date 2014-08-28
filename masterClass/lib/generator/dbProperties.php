<?php

/**
 * @author Jendro
 * class ini dipersiapkan untuk auto generate model dll sb
 * tapi nanti duluu hehe 
 */

class dbProperties extends model{
    
    public $table_fields = array(); // berisikan keterangan kolom lengkap pada table
    
    /** fungsi dibawah berguna untuk mendapatkan keterangan lengkap field pada table */
    protected function getColumnProperties($field){
        $q = $this->db->prepare("SHOW COLUMNS FROM $this->table WHERE Field = '$field'");
        $q->execute();
        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    /** method ini berfungsi mendapatkan semua nama field kolom pada table */
    public function getColumn(){
        $q = $this->db->prepare("DESCRIBE $this->table");
        $q->execute();
        $field = $q->fetchAll(PDO::FETCH_COLUMN);
        foreach($field as $val){
            $this->table_fields[]=$this->getColumnProperties($val);
        }
    }
      
}

?>