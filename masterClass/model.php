<?php

/**
 * @author Jendro
 */
 
class model{    
    public $beforeSaveStat = "";//input update both
    public $sistem = false;
    
    /**
     * - table = string, adalah nama table yang digunakan
     * - culomn = aray, berisikan kolom pada tabel ini yang kemungkinan besar akan diinput update atau delete, biasanya digunakan untuk auto input form
     * - input = object, berisikan objek yang berasal dari array $_POST yang dirubah menjadi objek
     * - upload_file = object, berisikan objek yang berasal dari variabel $_FILES yang dirubah menjadi bentuk objek
     * - parameter = array, parameter yang berasal dari link dan gabungan method $_POST yang dimasukan dalam PDO filter dan dieksekusi
    */
    public $column = array(); // berberntuk array isinya adalah kolom pada tabel yang kemungkinan besar diinput
    public $_GET = array(); // array yang berisikan node pada get element
    public $data = array(); //array berisikan input pada $_POST, yang nanti bisa diedit dang digunakan 
    public $table;
    public $addTable = "";
    public $lib = array();
    
    public function __construct(){
        $this->lib($this->lib);
    }
        
    public function resetInput(){
        $this->column = array();
        $this->_GET = array();
    }   
    
    /** memasukan bind parameter yang dipanggil pada query kedalam excec */
    public function paramFinal(){
        $PRE_PARAM = array_merge($this->data,RO::$LP);
        $parameter = array_merge($this->column,$this->_GET);
        foreach($parameter as $x=>$key){
            if(!empty($PRE_PARAM[$key]))
                RO::$DB->bindValue(":$key",$PRE_PARAM[$key]);
        }
    }
    
    public function bindParam($variabel, $value){
        $this->data[$variabel] = "$value";
    }
    
    /** ***********************/
    /**      BASIC QUERY      */
    /** ***********************/
    public function deleteQuery($condition){
        RO::$DB->prepare("Delete From $this->table $condition");
    }
    
    public function insertQuery($column,$value){
        RO::$DB->prepare("insert into $this->table($column) values($value)");
    }
    
    public function selectQuery($select="*",$condition=""){
        RO::$DB->prepare("select $select from $this->table $this->addTable $condition");
    }
    
    public function updateQuery($set,$condition=""){
        RO::$DB->prepare("update $this->table set $set $condition");
    }
    
    public function execute(){
        if(!$this->sistem)$this->paramFinal();
        RO::$DB->execute();
    }    
    
    /** ***********************/
    /**       DATA INPUT      */
    /** ***********************/
    
    public function postReader(){
        $this->data = $_POST;
    }
    
    /** ******************************/
    /**          DATA RETURN         */
    /** ******************************/
     public function dataCombo($key,$value,$condition = ""){
        $return = array();
        $data = $this->fetchAll("$key,$value",$condition,array(),true);
        foreach($data as $data){
            $return[$data[$key]] = $data[$value];
        }
        return $return;
    }    
    
    /** memberikan kembalian data pada model dengan bentuk objek tetapi isinya kosong
     *  digunakan untuk input pada form */
    public function dataModelReturn(){
        $data = array();
        foreach($this->column as $column){
            $data[$column] = "";
        }
        return $data;
    }
    
    public function fetch($select = "*",$condition = ""){
        $this->selectQuery($select,$condition);
        if(!$this->sistem)$this->paramFinal();
        $this->data = RO::$DB->fetch();
        if(empty($this->data)){
            $this->data = $this->dataModelReturn();
        }
        $this->beforeLoad();
        return $this->data;
    }
    
    public function fetchAll($select = "*",$condition = ""){
        $this->selectQuery($select,$condition);
        if(!$this->sistem)$this->paramFinal();
        return RO::$DB->fetchAll();
    }
    
    public function numRow($select = "*",$condition = ""){
        $this->selectQuery($select,$condition);
        if(!$this->sistem)$this->paramFinal();
        return RO::$DB->numRow();
        
    }
    /** ********************************/
    /**              BEFORE            */
    /** ********************************/
    
    /** yang digunakan untuk mengedit value pada variabel $_POST sebelum disimpan berbentuk array*/
    public function beforeSave(){
        
    }
    
    /** yang digunakan untuk mengedit value pada model sebelum diload*/
    public function beforeLoad(){
        
    }
    
    public function lib($lib = array()){
        foreach($lib as $key=>$lib){
            $this->$key = $this->$key = RO::lib($lib,$this);
        }
    }
    
    public function saveRecord(){
        return !empty($_POST);
    }
    
    public function cekUpdate($id){
        $ret = false;
        if(!empty($id))$ret = true;
        return $ret;
    }
}

?>