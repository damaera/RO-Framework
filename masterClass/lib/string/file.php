<?php 

class string_file{
    
    public function extention_finder($file){
        if(!empty($file)){
            $exp_file = explode(".",$file);
            $ext = $exp_file[(sizeof($exp_file)-1)];
        }else{
            $ext = "";
        }
        return $ext;
    }
    
    public function tmb_loader($file,$tmb = ""){
        if(!empty($file)){
            $ext = $this->extention_finder($file);
            $file = str_replace(".$ext","",$file);
            return $file.$tmb.".".$ext;
        }else{
            return "";
        }
    }
    
    public function is_image($file){
        $return = false;
        $ext = $this->extention_finder($file);
        if($ext=="jpg"||$ext=="png")$return = true;
        return $return;
    }
    
    public function is_pdf($file){
        $return = false;
        $ext = $this->extention_finder($file);
        if($ext=="pdf")$return = true;
        return $return;
    }
    
    public function file_to_image($file){
        $ext = $this->extention_finder($file);
        if($ext=="jpg"||$ext=="png"){
            
        }elseif($ext=="xls"||$ext=="xlsx"){
            $file = __BASEURL__."/image/asset/excel.jpg";
        }elseif($ext=="pdf"){
            $file = __BASEURL__."/image/asset/pdf.jpg";
        }elseif($ext=="doc"||$ext=="docx"||$ext=="rtf"){
            $file = __BASEURL__."/image/asset/word.jpg";
        }
        return $file;
    }
    
}

?>