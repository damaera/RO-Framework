<?php 

class model_form{
    /** ***********************/
    /**      NATIVE FORM      */
    /** ********************* */
    private $parent;
    
    public function __construct($parent){
        $this->parent = $parent;
    }    
    /**
     *  untuk membaca form langsung 
     *  Input berisikan value dari yang ingin diinput 
     *  filter : 
     *   -notEmpty : (array) tidak boleh kosong
    */
    
    /** mempersiapkan form */
    private function formPrepare($filter = array()){
        $len = sizeof($this->parent->column); // panjang dari kolom pada tabel yang mungkin diinput        
        $eror = false;
        $noEmpty = (isset($filter['notEmpty']))?$filter['notEmpty']:array();
        
        $ret_kolom = "";
        $ret_value = "";
        $ret_update = "";
        $ret_select = "";
        $ret_stat = false;
        $ret_parameter = array();
        
        if($len>0){
            $ret_stat = true;
            $kolom = $this->parent->column[0];
            $ret_kolom = "$kolom";
            $ret_value = "'".RO::$DB->inputFilter($this->parent->data[$kolom])."'";
            $ret_update = "$kolom = '".RO::$DB->inputFilter($this->parent->data[$kolom])."'";
            $ret_select = "where $kolom = '".RO::$DB->inputFilter($this->parent->data[$kolom])."'";
            if(in_array($kolom,$noEmpty)&&empty($this->parent->data[$kolom])){
                $ret_stat = false;
            }
            
            for($i = 1; $i<$len ;$i++){
                $kolom = $this->parent->column[$i];
                $ret_kolom .= ",$kolom";
                $ret_value .= ",'".RO::$DB->inputFilter($this->parent->data[$kolom])."'";
                $ret_update .= ",$kolom = '".RO::$DB->inputFilter($this->parent->data[$kolom])."'";
                $ret_select .= " AND $kolom = '".RO::$DB->inputFilter($this->parent->data[$kolom])."'";
                
                if(in_array($kolom,$noEmpty)&&empty($this->parent->data[$kolom])){
                    $ret_stat = false;
                    break;
                }
            }
        }
        $ret = (object)array("status"=>$ret_stat,"column"=>$ret_kolom,"value"=>$ret_value,"update"=>$ret_update,"select"=>$ret_select,"parameter"=>$ret_parameter);
        return $ret;
    }
    
    /** Langsung menginputkan dari form kepada tabel yang ditunjuk */
    public function inputForm($filter = array()){
        $this->parent->postReader();
        $this->parent->beforeSave();
        $output = $this->formPrepare($filter);
        if($output->status){
            $this->parent->insertQuery($output->column,$output->value);
            $this->parent->execute();
        }
        return $output->status;
    }
    
    /** Langsung mengupdate dari form kepada tabel yang ditunjuk berdasar parameter id yang ditempatkan pada :PARAM_1 */
    public function updateForm($filter = array(),$value = "PARAM_1",$parameter_key = "id"){
        $this->parent->_GET = array($value);
        return $this->updateFormCondition($filter,"where ".$parameter_key." = :$value");
    }
    
    protected function updateFormCondition($filter = array(),$condition){
        $this->parent->postReader();
        $this->parent->beforeSave();
        $output = $this->formPrepare($filter);
        if($output->status){
            $this->parent->updateQuery($output->update,$condition);
            $this->parent->execute();
        }
        return $output->status;
    }
    
    /** menghapus berdasar parameter id yang ditempatkan pada :PARAM_1 */
    public function deleteForm($value = "PARAM_1",$parameter_key = "id"){     
        $this->parent->postReader();
        $this->parent->_GET = array($value);
        $this->parent->deleteQuery("where $parameter_key = :$value");
        $this->parent->execute();
    }
    
    /** menggambil data pada database langusng dari form */
    public function selectForm($select = "*"){
        $return = "";
        $this->parent->postReader();
        $this->parent->beforeSave();
        $output = $this->formPrepare();
        if($output->status){
            $this->parent->selectQuery($select,$output->select);
            if(!$this->parent->sistem)$this->parent->paramFinal();
            $return = RO::$DB->fetchAll();
        }
        return $return;
    }
    
    /** mebghapus file biasanya digunakan sekaligus dengan widget form */
    public function deleteFile($file,$link,$force = false){
        if(!empty($_POST[$file."_delete_file"])||$force){
            if(file_exists($link))unlink($link);
            $link = "";
        }
        return $link;
    }
}

?>