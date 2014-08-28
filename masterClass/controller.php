<?php

abstract class controller{
    
    /**
     * - controller = string, mengatur nama controller
     * - $title = (string) mengatur judul
     * - layout = (string) mengatur layout yang digunakan
     * - head = (array)
     * - model = (object) mengatur model yang digunakan oleh class ini
     * - lib = (array) library yang diload otomatis saat controller dipanggil
    */
    
    protected $controller;
    protected $model;
    protected $title = "RO Site";
    protected $layout = "layout.php";
    protected $lib = array();
    
    function __construct($controller){
        if(isset($this->model))$this->model = RO::model($this->model);
        $this->controller = str_replace("Controller","",$controller);
        $this->loadLib();
    }
    
    public function __call($method, $args){
		$this->action404();
	}
    
    /** ******************************************************/
    /**                        TAMBAHAN                      */
    /** ******************************************************/
    
    /** berfungsi mengeset properties pada class jika kosong diset sesuai default*/
    private function loadLib(){
        foreach($this->lib as $key=>$lib){
            $this->$key = RO::lib($lib);
        }
    }
        
    protected function redirect($link){
        header("location: $link");
    }
    
    /** ******************************************************/
    /**                          VIEW                        */
    /** ******************************************************/
    
    /** 
     * method yang berfungsi merender file dan variabel yang mau dimasukan didalamnya
     * langsung dieksekusi link, kemudian dikembalikan, hasil kembalian adalah isi dari file yang harus disimpan dalam sebuah variabel
    */
    protected function renderFile($link,$var = array(),$nonsistem = true){
        ob_start();
            extract($var);
            if($nonsistem)$link = (file_exists("system/$link"))?"system/".$link:"../masterClass/RO/view/404.php";
            include_once($link);
            $return = ob_get_contents();
        ob_end_clean();
        return $return;
    }
    
    /** Menampilkan sebagian halaman web yang diperlukan saja*/ 
    protected function viewPartial($file = "index.php",$var = array()){
        $cekFile = explode("/",$file);
        $length = sizeof($cekFile);
        if($length>1){
            $file = $cekFile[$length-1];
            $cekFile[$length-1] = "";
            $folder = implode("/",$cekFile);
        }else{
            $folder = $this->controller;
        }
        return $this->renderFile("view/$folder/$file",$var);
    }
    
    /** Menampilkan seluruh halaman website beserta head dan layoutnya
     *  Merupakan method utama yang berhubungan langsung dengan view
     *  
     *  jika $file diisi dengan nama folder dan nama file akan otomatis ditacari lokasi folder dan viewnya berdasarkan root = system/view
     *  $var harus berbentuk array, key dari var nanti akan menjadi variabel yang akan dieksekusi pada file view
     */
 
    protected function  view($var_and_file = array("content"=>"index.php"),$var = array()){
        foreach($var_and_file as $variabel => $file){
            if(is_object($file)||is_array($file)||strpos($file,".php")==0)
                $$variabel = $file;
            else
                $$variabel = $this->viewPartial($file,$var);
        }
        include_once("system/view/component/$this->layout");
    }
    
    //pada key find return berupa array dengan name, operator dan value
    protected function finder($parameter = "PARAM_1"){
        $search = array('find','order');
        $return = array('find'=>'','order'=>'');
        if(empty(RO::$LP[$parameter])){
            return $return;
        }else{
            $arr = explode("*",RO::$LP[$parameter]);
            foreach($arr as $ar){
                $data = explode("=",$ar);
                if(sizeof($data)==2 && in_array($data[0],$search)){
                    $return[RO::$DB->inputFilter($data[0])]=RO::$DB->inputFilter($data[1]);
                }
            }
        }
        if(!empty($return['find'])){
            $find = array();
            $data = explode(",",urldecode($return['find']));
            foreach($data as $data){
                switch(preg_replace("/[A-Za-z0-9 ]/", '', $data)){
                    case ":":
                        $data = str_replace(":",":=:",$data);
                    break;
                    case "!:":
                        $data = str_replace("!:",":!=:",$data);
                    break;
                    case ">":
                        $data = str_replace(">",":>:",$data);
                    break;
                    case "<":
                        $data = str_replace("<",":<:",$data);
                    break;
                }
                //echo $data;
                $data_r = explode(":",$data);
                
                if(sizeof($data_r)==3){
                    if(!empty($find))$find.=" and";
                    $find[] = array("name"=>$data_r[0],"operator"=>$data_r[1],"value"=>$data_r[2]);
                }
            }
            $return['find'] = $find;
        }
        return $return;
    }
    
    public function outputJSON($data) {
		header("Content-Type: application/json;charset=utf-8");
		return json_encode($data);
	}
    
    /** ******************************************************/
    /**                     DEFAULT ACTION                   */
    /** ******************************************************/
    
    public function action404(){
        include_once("RO/view/404.php");
    }
    
    public function action403(){
        include_once("RO/view/403.php");
    }
    
    public function action401(){
        include_once("RO/view/000.php");
    }
    
    // Method default yang diload saat controller dipanggil
    public function actionIndex(){        
        $this->view("index.php");
    }
        
}

?>