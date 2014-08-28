<?php 

class model_w2ui{
    
    private $parent;
    private $request = array();
    // TODO W2UI Model dibuatkan class sendiri tetapi berhubungan dengan model
    /** ******************************/
    /**              W2UI            */
    /** ******************************/    
    public function __construct($parent){
        $this->parent = $parent;
    }
    
    private function postRequest(){
        $this->request = $_POST;
    }
    
    public function wGridCombo($field,$condition = ""){
        $data = $this->parent->fetchAll("id, $field text",$condition);
        $ret = array("items"=>$data);
        return $ret;
    }
    
    public function wGridSwitcher($recid="id",$select = "*",$condtition ="", $order = ""){
        $this->postRequest();
        switch($this->request['cmd']){
            case 'get-records':
                $return = $this->wGridGet($recid,$select,$condtition,$order);
            break;
            case 'delete-records':
                $return = $this->wGridDelete();
            break;
            case 'save-records':
                $return = $this->wGridUpdate();
            break;
        }       
        return $return; 
    }
        
    public function wGridGet($recid="id",$select = "*",$condition = "",$order = ""){
        $this->postRequest();
        $select_ = $select.", ".$recid." recid ";
        $sql = $this->wGridGetQuery($condition);
        $this->parent->selectQuery($recid,$sql['condition']);
        $total  = RO::$DB->fetchAll();
        if(!empty($order)){
            if(empty($sql['sort']))$order = " order by $order";
            else $order=",$order";
        }
        $this->parent->selectQuery($select_,$sql['condition'].$sql['sort'].$order.$sql['limit']);
        $data  = RO::$DB->fetchAll();
        $return = array("total"=>sizeof($total),"records"=>$data);
        return $return;
    }
    
    /** ***********************/
    /**        W2UI GRID      */
    /** ********************* */
    public function wGridUpdate() {
        $this->postRequest();
		$sql = Array();
        if(isset($this->request['changed'])){
    		foreach ($this->request['changed'] as $changed) {
    			$id = $changed['recid'];
                unset($changed['recid']);
                $set = "";
                foreach($changed as $key=>$val){
                    if ($set != "") $set .= ", ";
                    $set .= addslashes($key)." = '".addslashes($val)."'";
                }
                $this->parent->updateQuery($set,"where id = ".$id);
                RO::$DB->execute();
    		}
      }
      $return = array("status"=>"success");
      return $retur;
	}
    
    public function wGridDelete($keyField = "id") {
        $this->postRequest();
		$sql = Array();
		$recs = "";
		foreach ($this->request['selected'] as $k => $v) {
			if ($recs != "") $recs .= ", ";
			$recs .= "'".addslashes($v)."'";
		}
        
        if(!empty($recs)){
            $this->parent->deleteQuery("where $keyField IN($recs) ");
            RO::$DB->execute();
        }
        $return = array("status"=>"success","message"=>"berhasil dihapus");
		return $return;
	}
    
    private function wGridGetQuery($condition = "") {
        $sql = array();
        $penghubung = $this->request['search-logic'];
		$str = "";
		if (isset($this->request['search']) && is_array($this->request['search'])) {
			foreach ($this->request['search'] as $s => $search) {
				if ($str != "")$str .= " ".$penghubung." ";
				$operator = "=";
				$field 	  = $search['field'];
				$value    = "'".$search['value']."'";
				switch (strtolower($search['operator'])) {
					case 'begins with':
						//$operator = ($dbType == "postgres" ? "ILIKE" : "LIKE");
						$operator = "LIKE";
                        $value 	  = "'".$search['value']."%'";
						break;

					case 'ends with':
						//$operator = ($dbType == "postgres" ? "ILIKE" : "LIKE");
                        $operator = "LIKE";
						$value 	  = "'%".$search['value']."'";
						break;

					case 'contains':
						//$operator = ($dbType == "postgres" ? "ILIKE" : "LIKE");
                        $operator = "LIKE";
						$value 	  = "'%".$search['value']."%'";
						break;

					case 'is':
						$operator = "=";
						if (!is_int($search['value']) && !is_float($search['value'])) {
							$field = "LOWER($field)";
							$value = "LOWER('".$search['value']."')";
						} else {
							$value = "'".$search['value']."'";
						}
						break;

					case 'between':
						$operator = "BETWEEN";
						$value 	  = "'".$search['value'][0]."' AND '".$search['value'][1]."'";
						break;

					case 'in':
						$operator = "IN";
						$value 	  = "[".$search['value']."]";
						break;
				}
				$str .= $field." ".$operator." ".$value;
			}
		}

		// prepare sort
		$str2 = "";
		if (isset($this->request['sort']) && is_array($this->request['sort'])) {
			foreach ($this->request['sort'] as $s => $sort) {
				if ($str2 != "") $str2 .= ", ";
				$str2 .= $sort['field']." ".$sort['direction'];
			}
		}

		// build cql (for counging)
		$limit = (!isset($this->request['limit']))?50:$this->request['limit'];
		$offset = (!isset($this->request['offset']))?0:$this->request['offset'];
        $sql['condition'] = (!empty($str))?((empty($condition))?" where $str":" where $condition and $str"):((empty($condition))?'':"where $condition");
        $sql['sort'] = (!empty($str2))?" order by $str2":"";
		$sql['limit'] = " LIMIT ".$limit." OFFSET ".$offset;
		return $sql;
	} 
    
    /** ***********************/
    /**        W2UI FORM      */
    /** ********************* */
    public function wFormSwitcher(){
        $this->postRequest();
        switch($this->request['cmd']){
            case 'get-records':
                $return = $this->wGridGet($recid="id",$select = "*");
            break;
            case 'delete-records':
                $return = $this->wGridDelete();
            break;
            case 'save-records':
                $return = $this->wFormAdd();
            break;
        }       
    }
    
    public function wFormAdd(){
        $set = "";
        $key = "";
        $value = "";
        $this->postRequest();      
        $this->parent->data = $this->request['record'];
        $this->parent->beforeSave();
        if(!empty($this->request['recid'])){
            //update
            $recid = $this->request['recid'];
            foreach($this->parent->data as $k=>$v){
                if(in_array($k,$this->parent->column)){
                    if ($set != "") $set .= ", ";
                    $set .= addslashes($k)." = "."'".addslashes($v)."'";
                }
            }
            $this->parent->updateQuery($set,"where id = $recid");
            RO::$DB->execute();
            $record = "update";
            $id = $recid;
        }else{
            //insert
            foreach($this->parent->data as $k=>$v){
                if(in_array($k,$this->parent->column)){
                    if ($key != "") $key .= ", ";
                    if ($value != "") $value .= ", ";
                    $key .= addslashes($k);
                    $value .= "'".addslashes($v)."'";
                }
            }
            if(!empty($key)){
                $this->parent->insertQuery($key,$value);
                RO::$DB->execute();
            }
            $record = "new";
            $id = RO::$DB->lastInsertId();
        }
        
        $return = array("record"=>$record,"id"=>$id);
		return $return;
    }   
    
    //TODO: config diperbanyak seperti nama file, overwrite dll
    /** config seperti lib fileUpload */
    public function wUploadFile($field,$config = array()){
        $this->postRequest();
        $location = "";
        if(!empty($this->request['record'][$field])){
            foreach($this->request['record'][$field] as $file){
                if(empty($config['type'])||in_array($file['type'],$config['type'])){
                    $data_file =  base64_decode($file['content']);
                    $location = $config['mainFolder']."/".$config['folder']."/".$file['name'];
                    file_put_contents($location,$data_file);
                }                
            }
        }
        return $location;
    }
}

?>