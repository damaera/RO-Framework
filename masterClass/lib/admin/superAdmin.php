<?php
class admin_superAdmin extends controller{
    public $model = "model";
    
    public function actionIndex(){
        if(isset($_SESSION['tipe_user'])&&$_SESSION['tipe_user']=="superAdmin"){
            $this->actionAdmin();
        }
        else{
            if(isset($_POST['submit'])){
                $this->model->postReader();
                //if($this->model->data['nama'] == "admin"&&$this->model->data['password'] == "password"){
                    $_SESSION['tipe_user'] = "superAdmin";
                    header("location:".__BASEURL__."ro-admin/admin");
                //}
            }
            include_once("view/index.php");
        }
    }
    
    public function actionLog(){
        $content = "";
        include_once("view/admin.php");
    }
    
    public function actionAdmin(){
        $content = $this->renderFile("lib/admin/view/tipe_user.php",array(),false);
        include_once("view/admin.php");
    }
    
    /** menampilkan access pada berdasarkan nama user yang dipanggil */
    public function actionListAccess(){
        $tipe = RO::lib("access_tipeUser");
        $tipe->_GET = array("PARAM_1");
        $tipe = $tipe->fetch("nama,id","WHERE id = :PARAM_1");
        $user = $tipe['nama'];
        $id_user = $tipe['id'];
        $access = RO::lib("access_accessRule");
        $access->_GET = array("PARAM_1");
        $access->addTable = ",ro_action A,ro_controller C";
        $access->table = "ro_akses AC";
        $validasi = $access->controllerVal(":PARAM_1");
        $data = $access->fetchAll("AC.id id,C.nama controller,A.nama action,akses,redirect","WHERE AC.id_controller = C.id AND AC.id_action=A.id AND id_tipe_user = :PARAM_1 order by AC.id_controller,akses");
        include_once("view/listAccess.php");
    }
    
    /** Merubah hak akses */
    public function actionChangeAccess(){
        $access = RO::lib("access_accessRule");
        $access->table = "ro_akses";
        $access->addTable = "";
        $query = $access->updateQuery("akses = IF(akses='Y','N','Y')","WHERE id = :PARAM_1");
        $access->_GET = array("PARAM_1");
        $access->execute($query);
        $return = $access->fetch("akses","WHERE id= :PARAM_1");
        echo $return['akses'];
    }
    
    /** Memperbarui hak akses */
    public function actionRefreshAccess(){
        $access = RO::lib("access_accessRule");
        $access->table = "ro_akses";
        $access->addTable = "";
        $access->refreshAccess();
        header("location: admin");
    }
    
    /** Menambah tipe user baru */
    public function actionAddTypeUser(){
        $access = RO::lib("access_accessRule");
        $access->table = "ro_akses";
        $access->addTable = "";
        if(!empty($_POST['submit'])){
            $access->postReader();
            $access->createUser($access->data['nama']);
        }
        header("location: admin");
    }
    
    public function actionDeleteTypeUser(){
        $access = RO::lib("access_accessRule");
        $access->table = "ro_akses";
        $access->addTable = "";
        $query = $access->deleteQuery("WHERE id_tipe_user = :PARAM_1");
        $access->_GET = array("PARAM_1");
        $access->execute($query);
        $tipe = RO::lib("access_tipeUser");
        $tipe->_GET = array("PARAM_1");
        $query =  $tipe->deleteQuery("WHERE id = :PARAM_1");
        $tipe->execute($query);
        header("location: ../admin");
    }
    
    /** mengganti redirect */
    public function actionChangeRedirect(){
        $access = RO::lib("access_accessRule");
        $access->table = "ro_akses";
        $access->addTable = "";
        $query = $access->updateQuery("redirect = IF(redirect = 1, 3, IF(redirect = 3, 4, 1))","WHERE id = :PARAM_1");
        $access->_GET = array("PARAM_1");
        $access->execute($query);
        $return = $access->fetch("redirect","WHERE id=:PARAM_1");
        echo $return['redirect'];
    }
    
    /** menghapus access berdasar id pk */
    public function actionDeleteAccess(){
        $access = RO::lib("access_accessRule");
        $access->table = "ro_akses";
        $access->addTable = "";
        $access->postReader();
        $query = $access->deleteQuery("WHERE id = ".$access->data['id']);
        $access->execute($query);
        echo "Berhasil Dihapus";
    }
    
    public function actionUpdateTypeUser(){
        $tipe = RO::lib("access_tipeUser");
        $tipe->postReader();
        $query = $tipe->updateQuery("nama = '".$tipe->data['nama']."'","WHERE id =".$tipe->data['id']);
        print_r($query);
        $tipe->execute($query);
        header("location: admin");
    }
    
    public function actionLogout(){
        session_destroy();
        header("location:../");
    }
    
}

?>