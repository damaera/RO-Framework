<?php
include_once "/../string/file.php";
class form_form extends string_file{
    
    private function attributeSetter($attribute = array()){
        $data = ""; 
        foreach($attribute as $key => $value){
            $data .= $key."='$value' ";
        }
        return $data;
    }
    
    public function inputHidden($name,$value){
        $value = ($value)?"value = '".$value."'":"";
        $return  = "<input $value name=$name type='hidden' />";
        return $return;
    }
    
    public function inputText($name,$value,$default = "",$attribute = array()){
        $value = ($value)?"value = '".$value."'":"";
        if(empty($value)&&$default)$value = "value = '".$default."'";
        $attr = $this->attributeSetter($attribute);
        $return  = "<input $value name=$name type='text' $attr />";
        return $return;
    }
    
    public function inputEmail($name,$value,$default = "",$attribute = array()){
        $value = ($value)?"value = '".$value."'":"";
        if(empty($value)&&$default)$value = "value = '".$default."'";
        $attr = $this->attributeSetter($attribute);
        $return  = "<input $value name=$name type='email' $attr />";
        return $return;
    }
    
    public function inputDate($name,$value,$attribute = array()){
        $value = ($value)?"value='".$value."'":"";
        $attr = $this->attributeSetter($attribute);
        
        $return  = "<input type='date' name='$name' $value $attr/>";
        return $return;
    }
    
    public function inputDateTime($name,$value,$attribute = array()){
        $value = ($value)?"value='".$value."'":"";
        $attr = $this->attributeSetter($attribute);
        
        $return  = "<input type='datetime-local' name='$name' $value $attr/>";
        return $return;
    }
        
    public function textarea($name,$value,$attribute = array()){
        $value = ($value)?$value:"";
        $attr = $this->attributeSetter($attribute);
        $return  = "<textarea name=$name $attr />$value</textarea>";
        return $return;
    }
    
    /** 
     *  $name = (string) nama input
     *  $value = (string) value isinya, biasanya edit
     *  $data = (array) nama => value
     *  $atrribute = (array) html option
    */
    public function radiobox($name,$value,$data=array(),$default= ""){
        $value = ($value)?$value:"";
        $checked = "";
        $return = "";
        if(empty($value)&&!empty($default)){
            $value = $default;
        }
        foreach($data as $name_data=>$value_data){
            if($value_data == $value)$checked = "checked='checked'";
            else $checked = "";
            $return .= "$name_data <input type='radio' name='$name' value='$value_data' $checked />";
        }
        return $return;
    }
    
    public function comboBox($name,$value,$data=array(),$default= ""){
        $value = ($value)?$value:"";
        $selected = "";
        $return = "";
        $length = sizeof($data);
        if(empty($value)&&!empty($default)){
            $value = $default;
        }
        if($length>0){
            $return .= "<select name='$name'>";
            foreach($data as $value_data=>$name_data){
                if($value_data == $value)$selected = "selected='selected'";
                else $selected = "";
                $return .= "<option value='$value_data' $selected />$name_data</option>";
            }
            $return .= "</select>";
        }
        return $return;
    }
    
    public function file($name,$value,$tmb_name = ""){
        $return = "";
    
        if(!empty($value)){
            $bukan_gambar = "";
            if(!$this->is_image($value)){
                $bukan_gambar = "style=\"width:100px\"";
            }
            $return .= "
            <img $bukan_gambar src = '".$this->tmb_loader($this->file_to_image($value),$tmb_name)."'/><br />
            <input type='checkbox' name='".$name."_delete_file' value=1/> Hapus File
            <input name='".$name."_file_hidden' value='$value' type='hidden' /><br />Edit : 
            ";
        }
        
        $return .= "<input name='$name' type='file' />";
        return $return;
    }
    
    public function chooseOne($key,$pilihan = array()){
        $return = "";
        if(!empty($pilihan[$key]))$return = $pilihan[$key];
        return $return;
    }
}
?>