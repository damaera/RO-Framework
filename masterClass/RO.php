<?php

/** RO adalah sebuah framework PHP yang menggunakan desain patern module MVC
 * @author Jendro Purnomo
 * @email jendropurnomo@gmail.com
 * @last-update 10 mei 2014
 * framework ini masih dikembangkan dan belum direlease untuk publik, tetapi bebas digunakan bagi siapapun yang menginginkan
 * opensource
 * v 2.1
 */ 

class RO{
    
    private $controller;
    private $action = "actionIndex";

    public static $DB;
    public static $LP = array();//array, input melalui link yang diproses oleh action controller
    public static $instance = array();
    
    /** ********************************************************** */
    /**                        LINK READER                         */
    /** ********************************************************** */
    
    /** fungsi dibawah berfungsi untuk membaca link dan mendapatkan controller,action dan query */
    private function linkReader($link){
        $exp_ = array_filter(explode("/",$link));//dipecah berdasar tanda /
        $exp_link = array();
        foreach($exp_ as $val){
            $exp_link [] = $val;
        }
        if(count($exp_link)>0){
            $controller = $exp_link[0]."Controller";
            $controller_path = __WEBSITE__."system/controller/$controller.php";
            if(__ROADMIN__&&$exp_link[0]=="ro-admin"){
                RO::lib("admin_superAdmin");
                $this->controller = "admin_superAdmin";
            }
            elseif(file_exists($controller_path)){
                require_once __WEBSITE__."system/controller/$controller.php";
                if(class_exists($controller))
                    $this->controller = $controller;
            }else{
                $this->controller = __MAINCONT__;
                require_once __WEBSITE__."system/controller/$this->controller.php";
            }
            if(!empty($exp_link[1]))
                    $this->action = "action".ucfirst($exp_link[1]);
            
            // kemungkinan memiliki query atau tidak
            $query = array_slice($exp_link,2);
            if(sizeof($query>0))$this->queryMaker($query);
        }else{
            $this->controller = __MAINCONT__;
            require_once __WEBSITE__."system/controller/$this->controller.php";
        }              
    }
    
    public static function load($class,$parameter = ""){
        if(empty(RO::$instance[$class])) {
			RO::$instance[$class] = new $class($parameter);
		}
        return RO::$instance[$class];
    }
    
    public static function lib($class_lib,$parameter = "",$class = ""){
        $class_name = empty($class)?$class_lib:$class;
        $path = str_replace("_","/",$class_lib);
        include_once "lib/$path.php";
        return self::load($class_name,$parameter);
    }
    
    public static function model($class_model){
        $model = __WEBSITE__."system/model/$class_model.php";
        if(file_exists($model)){
            include_once $model;
            $class = self::load($class_model);
            $class->resetInput();
        }else{
            if(empty(RO::$instance[$class_model])){
    			RO::$instance[$class_model] = new model();
    		}
            $class = RO::$instance[$class_model];
            $class->table = $class_model;
            $class->resetInput();
        }
        return $class;
    }
    
    /** mengambil parameter pada link */
    private function queryMaker($input){
        $huruf = array(0=>'PARAM_1',1=>'PARAM_2',2=>'PARAM_3',3=>'PARAM_4',4=>'PARAM_5');
        $ret = array();
        foreach($input as $key=>$val){
            $ret[$huruf[$key]] = addslashes($val);
        }
        self::$LP = $ret;
    }
    
    /** Berfungsi mengecek user tersebut berhak mengakses method tersebut atau tidak */
    private function accessCek(){
        $actor = (!empty($_SESSION['tipe_user']))?$_SESSION['tipe_user']:"guest";
        $cek = self::lib("access_accessRule");
        $hasil = $cek->cekAccess($this->controller,$this->action,$actor);
        if(empty($hasil['akses'])){
            $hasil['akses'] = "N";
            $hasil['redirect'] = 4;
        }
        if(!empty($hasil['redirect'])&&$hasil['akses']=="N"){
            $this->action = "action40".$hasil['redirect'];
        }
    }
        
    /** ********************************************************** */
    /**                         SETTING                            */
    /** ********************************************************** */

    
    /** ********************************************************** */
    /**                           MAIN                             */
    /** ********************************************************** */
    
    function __construct($library = array()){
        $link = parse_url($_SERVER["REQUEST_URI"]);//link dibaca
        $link = $link['path'];//diambil path link 
        if(__BASEURL__ == "/")
            $link = substr($link,1);
        else
            $link = str_replace(__BASEURL__, "", $link);//dibuang yang sama dengan base
        
        if(!empty($link)){
            $this->linkReader($link);
        }else{
            $this->controller = __MAINCONT__;
            $this->action = "actionIndex";
            require_once __WEBSITE__."system/controller/$this->controller.php";
        }
        
        if(__ENABLEDB__){
            
            /** PDO DATABASE 
			self::$db_stat = new PDO(__DBSTRING__, __DBUSER__, __DBPASS__);
			self::$db_stat -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$db_stat -> setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            // */
            
            /** MYSQL DATABASE */
            include_once("database/db_mysql.php");
            self::$DB = new  db_mysql(__DBHOST__,__DBNAME__,__DBUSER__,__DBPASS__);
            
            /** MYSQLI DATABASE */
            
            /** POSTGREE DATABASE */
            
            if(__ACCESSRULE__){
                $this->accessCek();
            }
            
        }
        date_default_timezone_set(__TZ__);
        $controller = new $this->controller($this->controller);
        foreach($library as $key=>$lib){
            $controller->$key = self::lib($lib);
        }
        $action = $this->action;
        $controller->$action();
    }
    
}

?>