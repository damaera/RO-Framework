<?php

class file_fileUpload{
    private $error = false;
    private $config = array();
    private $folder_was_slaced = false;
    private $file_upload;
    private $file_name;
    private $file_ext;
    private $file_type;
    private $tmp_loc;
    
    private $main_folder;
    private $folder = "";
    private $replace = false;
    
    private $typeFilter;
    private $extFilter;
    private $typeAllow = array();
    private $extAllow = array();
    
    private $createFolder;
    
    private $upload_loc;
    private $link_edit;
    
    private $edit = false;
    
    function __construct(){
        $this->fileReader();
    }
    
    /** jika value kosong maka yang dikembailkan nilai default, dimana default juga digunakan sebagai nama variabel */
    private function valueCheck($default,$value){
        return ($value)?$value:$default;
    }
    
    private function configCheck($default,$value){
        return (isset($this->config[$value]))?$this->config[$value]:$default;
    }
    
    private function emptyEror($value){
        if(empty($value)){
            $this->error = true;
            $ret = "";
            echo "error line 45";
        }else{
            $ret = $value;
        }
        return $ret;
    }
    
    /** memfilter type apa saja yang boleh diupload */
    private function typeFilter(){
        if($this->typeFilter){
            if(!in_array($this->file_type,$this->typeAllow)){
                $this->error = true;
                echo "error line 57";
            }
        }
    }
    
    /** memfilter extensi apa saja yang boleh diupload */
    private function extFilter(){
        if($this->extFilter){
            if(!in_array($this->file_ext,$this->extAllow)){
                $this->error = true;
                echo "error line 67";
            }
        }
    }
    
    /** mengecek apakah folder ada atau tidak jika tidak ada maka akan dicek apakah boleh membuat folder, jika tidak boleh maka akan eror */
    private function createFolder(){
        if(!$this->checkFileExist($this->main_folder."/".$this->folder)&&$this->folder!=""){
            if($this->createFolder){
                mkdir($this->main_folder."/".$this->folder);
            }else{
                $this->error = true;
                echo "error line 79";
            }
        }
    }
    
    /** menyimpan data pada variabel $_FILES kedalam $this->file */
    private function fileReader(){
        $this->file_upload = (object)$_FILES;
    }
    
    private function checkFileExist($file){
        return file_exists($file);
    }
    
    
    /** berfungsi membuat nama selanjutnya jika nama sebelumnya sudah ada */
    private function nextNameGenerator(){
        $this->pathGenerator();
        $i = 1;
        $nama = $this->file_name;
        while($this->checkFileExist($this->upload_loc)){
            $this->file_name = $nama."($i)";
            $i++;
            $this->pathGenerator();
        }
    }
    
    /** memisahkan extensi dan nama file */
    private function filenameExtention($file){
        $ext = explode(".",$file);
        $ext = $ext[sizeof($ext)-1];
        $name = str_replace(".$ext","",$file);
        $ret = array("name"=>$name,"ext"=>$ext);
        return $ret;
    }
    
    /** mempersiapkan file */
    private function filePrepare($file_name){
        $file_master = $this->file_upload->$file_name;
        //if(empty($file_master['name'])){$this->error = true;}
        $file = $this->file_upload->$file_name;
        $file_ext = $this->filenameExtention($file['name']);
        $this->file_name = $file_ext['name'];
        $this->file_ext = $file_ext['ext'];
        $this->tmp_loc = $file['tmp_name'];
        $this->file_type = $file['type'];
    }
    
    private function configPrepare($config){
        $this->config = $config;
        $this->link_edit = $this->configCheck("",'link_edit');
        if(empty($this->link_edit)){
            $this->main_folder = $this->valueCheck("",$this->emptyEror($config['main_folder']));
            $this->folder = $this->configCheck("",'folder');
            $this->file_name = $this->configCheck("",'name');
        }
        $this->replace = $this->configCheck(false,'replace');
        $this->createFolder = $this->configCheck(false,'create_folder');
        $this->typeFilter = $this->configCheck(false,'type_filter');
        $this->extFilter = $this->configCheck(false,'ext_filter');
        $this->typeAllow = $this->configCheck(array(),'type_allow');
        $this->extAllow = $this->configCheck(array(),'ext_allow');
        
    }
    
    /** digunakan untuk edit */
    private function linkBreaker(){
        $link = explode("/",$this->link_edit);
        $file = $link[sizeof($link)-1];
        $main_folder = $link[0];
        $link = str_replace("$file","",$this->link_edit);
        $link = str_replace("$main_folder/","",$link);        
        $file = explode(".",$file);
        $this->file_name = $file[0];
        $this->file_ext = $file[1];
        $this->folder = $link;
        $this->main_folder = $main_folder;
    }
    
    private function editCheck(){
        if(!empty($this->link_edit))$this->edit = true;
    }
    
    /** membuat lokasi tempat menyimpan file */
    private function pathGenerator(){
        if(!empty($this->folder)&&!$this->folder_was_slaced){
            $this->folder = $this->folder."/";
            $this->folder_was_slaced = true;
        }
        $this->upload_loc = $this->main_folder."/".$this->folder.$this->file_name.".".$this->file_ext;
    }
    
    private function upload(){
        move_uploaded_file($this->tmp_loc,$this->upload_loc);
    }
    
    /** menyimpan file kedalam hardisk / merubah nama dan yang lainnya 
     *  main_folder = (string) karena ini pake RO dan secara default sudah ada htaccess, jd harus ada main folder tempat menyimpan yang nanti diedit pada htaccess secara manual
     *  folder = (string)folder tempat menyimpan
     *  name = (string) nama file
     *  replace = (bool) jika file ada diijinkan untuk mereplace, deff = false
     *  create_folder = (bool) jika folder tidak ditemukan diijinkan membuat folder, deff = false
     *  typeFilter = (bool) apakah ada filter type ,deff = false
     *  type_allow = (array) type apa saja yang diperbolehkan
     *  extFilter = (bool) extensi apa saja yang diperbolehkan, deff = false
     *  ext_allow = (array) extensi apa saja yang diiperbolehkan untuk diupload
     *  link_edit = (string) berisikan link gambar yang sudah disimpan, digunakan untuk mengedit gambar
     * 
     *  return nama lokasi tempat file disimpan
    */
    public function fileUploader($file_name,$config = array()){
        $ret = array(
                'link'=>'',
                'name'=>'',
                'ext'=>''
                );
        if(!empty($this->file_upload->$file_name)){
            $this->configPrepare($config);
            $this->filePrepare($file_name);
            $this->createFolder();
            $this->typeFilter();
            $this->extFilter();
            $this->editCheck();
            if(!$this->error){
                if(!$this->edit){
                    $this->pathGenerator();
                    if(!$this->replace)$this->nextNameGenerator();
                }else{
                    $this->linkBreaker();
                    $this->upload_loc = $this->link_edit;
                }
    
                $this->upload();
                $ret = array(
                    'link'=>$this->main_folder."/".$this->folder.$this->file_name.".".$this->file_ext,
                    'name'=>$this->main_folder."/".$this->folder.$this->file_name,
                    'ext'=>$this->file_ext
                );
            }
            $_POST[$file_name] = $ret['link'];
        }
        //print_r($this);
        return $ret;        
    }
    
}

?>