<?php

/**
 * @author Jendro
 */

class access_accessRule extends model{    
    private $file = array();
    public $list = array();
    public $table = "ro_akses";
    
    // Membaca action pada controller
    private function actionFind($class){
        $method = get_class_methods($class);
        $ret = array();
        foreach($method as $val){
            if(preg_match("/action/",$val)&& !preg_match("/action+[0-9]/",$val)){
                $ret[] = $val;
            }
        }
        $this->list[$class] = $ret;
    }

    //Membaca direktori dan mendapatkan file controller
    private function dirRead(){
        $this->file = scandir(__WEBSITE__."system/controller");
    }
    
    //filter file controller
    private function controllerReader(){
        foreach($this->file as $controller){
            $class = str_replace(".php","",$controller);
            if(strlen($class)>5){
                include_once __WEBSITE__."/system/controller/$class.php";;
            }
            if(preg_match("/Controller/",$class)){
                $this->actionFind($class);
            }
        }
    }
    
    //Cek Controller User Action Exist
    private function cekCAExist($controller,$action,$tipe){
        $this->selectQuery("akses","WHERE id_controller=$controller and id_action=$action and id_tipe_user=$tipe");
        $rows = RO::$DB->fetch();
        return $rows;
    }
    
    private function checkUser($nama){
        $tipe_user = RO::lib("access_tipeUser");
        $cek_tipeUser = $tipe_user->cekTipeUser($nama);
        
        if(isset($cek_tipeUser['id']))$tipe_user = $cek_tipeUser['id'];
        else{
            $tipe_user->tambah($nama);
            $tipe_user = RO::$DB->lastInsertId();
        }   
        return $tipe_user;
    }
    
    private function createUserFinal($id){
        
        $controller = RO::lib("access_controller");
        $action = RO::lib("access_action");
        
        $this->mulai();      
        foreach($this->list as $list_ctrl=>$list_aksi){
            $cek_controller = $controller->cekController($list_ctrl);
            if(isset($cek_controller['id']))$controller_id = $cek_controller['id'];
            else{
                $controller->tambah($list_ctrl);
                $controller_id = RO::$DB->lastInsertId();
            }
            foreach($list_aksi as $list_action){
                $cek_aksi = $action->cekAction($list_action);
                if(isset($cek_aksi['id']))$action_id= $cek_aksi['id'];
                else{
                    $action->tambah($list_action);
                    $action_id = RO::$DB->lastInsertId();
                }
                $this->bindParam("controller",$controller_id);
                $this->bindParam("action",$action_id);
                $cek_akses = $this->cekCAExist($controller_id,$action_id,$id);
                if(empty($cek_akses)){
                    $this->insertQuery("id_tipe_user,id_controller,id_action,akses,redirect","'$id',:controller,:action,'Y',3");  
                    $this->_GET = array("controller","action");
                    $this->execute();
                }
            }
        }
    }
    
    private function mulai(){
        $this->dirRead();
        $this->controllerReader();
    }
    
    public function createSuperAdmin(){
        $this->mulai();
        $this->actionFind("admin_superAdmin");
        $this->createUser('superAdmin');
        
    }
    
    public function createUser($nama){
        if($nama!='superAdmin')$this->list["admin_superAdmin"]=array("actionIndex");
        $id = $this->checkUser($nama);
        $this->createUserFinal($id);
    }
    
    public function cekAccess($controller,$action,$actor){
        $this->addTable = ",ro_action A,ro_controller C,ro_tipe_user T";
        $this->table = "ro_akses AC";
        $this->selectQuery("akses,redirect","WHERE AC.id_controller = C.id and AC.id_action=A.id and AC.id_tipe_user=T.id and A.nama ='$action' and C.nama='$controller' and T.nama='$actor'");
        return RO::$DB->fetch();
    }
    
    public function refreshAccess(){
        $tipe = RO::lib("access_tipeUser");
        $data = $tipe->fetchAll("id,nama");
        foreach($data as $data){
            $this->createUserFinal($data['id']);
        }
        $this->createSuperAdmin();
    }
    
    public function controllerVal($id){
        $tipe = RO::lib("access_tipeUser");
        $tipe->_GET = array(str_replace(":","",$id));
        $admin = $tipe->fetch("id","WHERE id = $id AND nama = 'superAdmin'");
        if($admin)$this->actionFind("admin_superAdmin");
        else $this->list["admin_superAdmin"]=array("actionIndex");
        $this->mulai();
        return $this->list;
    }
}

?>