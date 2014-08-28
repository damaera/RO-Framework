<?php

/**
 * @author Jendro
 */

class access_tipeUser extends model{
    
    private $file = array();
    public $list = array();
    public $table = "ro_tipe_user";
    
    public function tambah($nama){
        $this->insertQuery("nama","'$nama'");
        $this->execute();
    }
    
    public function hapus($nama){
        $this->deleteQuery("WHERE nama ='$nama'");
        $this->execute();
    }
    
    public function cekTipeUser($nama){
        $this->selectQuery("id","WHERE nama = '$nama'");
        return RO::$DB->fetch();
    }
    
    public function listTipeUser(){
        return $this->fetchAll("id,nama","WHERE nama != 'superAdmin'");
    }
}

?>